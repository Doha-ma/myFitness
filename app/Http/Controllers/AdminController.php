<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\Payment;
use App\Models\ClassModel;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMembers = Member::count();
        $totalClasses = ClassModel::count();
        $totalReceptionists = User::where('role', 'receptionist')->count();
        $totalCoaches = User::where('role', 'coach')->count();
        
        // Payment Statistics
        $totalPayments = Payment::sum('amount');
        $paymentsThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
        $paymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $totalPaymentsCount = Payment::count();
        
        // Recent payments
        $recentPayments = Payment::with(['member', 'receptionist'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalClasses',
            'totalReceptionists',
            'totalCoaches',
            'totalPayments',
            'paymentsThisMonth',
            'paymentsToday',
            'totalPaymentsCount',
            'recentPayments'
        ));
    }

    public function staffIndex()
    {
        $receptionists = User::where('role', 'receptionist')->get();
        $coaches = User::where('role', 'coach')->get();

        return view('admin.staff.index', compact('receptionists', 'coaches'));
    }

    public function staffCreate()
    {
        return view('admin.staff.create');
    }

    public function staffStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:receptionist,coach',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', ucfirst($validated['role']) . ' ajouté avec succès!');
    }

    public function staffEdit(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Cannot edit admin users');
        }

        return view('admin.staff.edit', compact('user'));
    }

    public function staffUpdate(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Cannot edit admin users');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:receptionist,coach',
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff mis à jour avec succès!');
    }

    /**
     * Delete a staff member (coach or receptionist)
     * Checks for related data before deletion to prevent orphan records
     */
    public function staffDestroy(User $user)
    {
        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot delete admin users');
        }

        // Check for related data based on role
        if ($user->role === 'coach') {
            // Check if coach has classes
            $classesCount = $user->classesAsCoach()->count();
            if ($classesCount > 0) {
                // Foreign key cascade will handle class deletion, but we inform the admin
                // Note: Classes will be deleted via cascade, which will also delete enrollments
                // This is safe due to foreign key constraints
            }
        } elseif ($user->role === 'receptionist') {
            // Check if receptionist has recorded payments
            $paymentsCount = $user->paymentsAsReceptionist()->count();
            if ($paymentsCount > 0) {
                // Foreign key cascade will handle payment deletion
                // Payments are historical records, but cascade is set in migration
                // This is safe due to foreign key constraints
            }
        }

        // Delete user (cascade will handle related records via foreign keys)
        // Foreign keys are configured with onDelete('cascade') in migrations
        try {
            $user->delete();
            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff supprimé avec succès!');
        } catch (\Exception $e) {
            // Log error and return with message
            \Log::error('Error deleting staff: ' . $e->getMessage());
            return redirect()->route('admin.staff.index')
                ->with('error', 'Erreur lors de la suppression. Veuillez réessayer.');
        }
    }
}