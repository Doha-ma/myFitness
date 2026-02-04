<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        $totalMembers = Member::count();
        $totalPaymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $recentMembers = Member::latest()->take(5)->get();

        return view('receptionist.dashboard', compact(
            'totalMembers',
            'totalPaymentsToday',
            'recentMembers'
        ));
    }

    public function membersIndex(Request $request)
    {
        $query = Member::with(['classes', 'payments.subscriptionType']);

        // Filter by enrolled class
        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        // Filter by subscription type
        if ($request->filled('subscription_type_id')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('subscription_type_id', $request->subscription_type_id)
                  ->orderBy('payment_date', 'desc')
                  ->limit(1);
            });
        }

        $members = $query->latest()->paginate(15);
        
        // Get filter options
        $classes = \App\Models\ClassModel::with('coach')->get();
        $subscriptionTypes = \App\Models\SubscriptionType::where('is_active', true)->get();

        return view('receptionist.members.index', compact('members', 'classes', 'subscriptionTypes'));
    }

    public function membersCreate()
    {
        // Load available classes for course selection
        $classes = \App\Models\ClassModel::with('coach')->get();
        return view('receptionist.members.create', compact('classes'));
    }

    public function membersStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            // Course selection validation (optional)
            'classes' => 'nullable|array',
            'classes.*' => 'exists:classes,id',
        ]);

        // Create member (existing functionality preserved)
        $member = Member::create($validated);

        // Attach selected courses to member using existing enrollments pivot table
        // Uses existing many-to-many relationship via Member->classes()
        if ($request->has('classes') && is_array($request->classes) && count($request->classes) > 0) {
            // Prepare enrollment data with enrollment_date
            $enrollmentData = [];
            foreach ($request->classes as $classId) {
                // Check if enrollment already exists to prevent duplicates
                $exists = \App\Models\Enrollment::where('member_id', $member->id)
                    ->where('class_id', $classId)
                    ->exists();
                
                if (!$exists) {
                    $enrollmentData[$classId] = [
                        'enrollment_date' => $validated['join_date'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Attach courses using existing relationship
            if (!empty($enrollmentData)) {
                $member->classes()->attach($enrollmentData);
            }
        }

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Membre ajouté avec succès!');
    }

    public function membersEdit(Member $member)
    {
        return view('receptionist.members.edit', compact('member'));
    }

    public function membersUpdate(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Membre mis à jour avec succès!');
    }

    /**
     * Delete a member
     * Detaches member from all classes before deletion
     * Prevents deletion if member has payments (to preserve historical records)
     */
    public function membersDestroy(Member $member)
    {
        try {
            // Check if member has payments - preserve historical payment records
            $paymentsCount = $member->payments()->count();
            if ($paymentsCount > 0) {
                return redirect()->route('receptionist.members.index')
                    ->with('error', 'Impossible de supprimer ce membre car il a ' . $paymentsCount . ' paiement(s) enregistré(s). Les paiements sont des enregistrements historiques et doivent être conservés.');
            }
            
            // Detach member from all classes using existing many-to-many relationship
            // This removes records from enrollments pivot table
            $member->classes()->detach();
            
            // Delete member (no payments exist, so safe to delete)
            $member->delete();
            
            return redirect()->route('receptionist.members.index')
                ->with('success', 'Membre supprimé avec succès!');
        } catch (\Exception $e) {
            // Log error and return with message
            \Log::error('Error deleting member: ' . $e->getMessage());
            return redirect()->route('receptionist.members.index')
                ->with('error', 'Erreur lors de la suppression. Veuillez réessayer.');
        }
    }

    public function paymentsIndex()
    {
        $payments = Payment::with(['member', 'receptionist', 'subscriptionType'])
            ->latest()
            ->paginate(15);

        return view('receptionist.payments.index', compact('payments'));
    }

    public function paymentsCreate()
    {
        $members = Member::where('status', 'active')->get();
        $subscriptionTypes = \App\Models\SubscriptionType::where('is_active', true)->get();
        return view('receptionist.payments.create', compact('members', 'subscriptionTypes'));
    }

    public function paymentsStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'subscription_type_id' => 'nullable|exists:subscription_types,id',
            'amount' => 'nullable|numeric|min:0', // Optional if subscription is selected
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

        // If subscription type is selected, calculate amount automatically
        if ($request->filled('subscription_type_id')) {
            $subscriptionType = \App\Models\SubscriptionType::findOrFail($request->subscription_type_id);
            $validated['amount'] = $subscriptionType->final_price;
        } else {
            // Fallback to manual amount if no subscription selected (backward compatibility)
            $request->validate([
                'amount' => 'required|numeric|min:0',
            ]);
        }

        Payment::create([
            ...$validated,
            'receptionist_id' => auth()->id(),
        ]);

        return redirect()->route('receptionist.payments.index')
            ->with('success', 'Paiement enregistré avec succès!');
    }

    /**
     * Generate PDF invoice for a payment
     * Route: GET /receptionist/payments/{payment}/invoice
     * Only accessible to receptionists (enforced by route middleware)
     */
    public function paymentsInvoice(Payment $payment)
    {
        // Load necessary relationships for PDF
        $payment->load(['member', 'receptionist']);
        
        // Generate PDF using dedicated Blade view
        $pdf = Pdf::loadView('pdf.payment-invoice', compact('payment'));
        
        // Generate filename with payment ID and date
        $filename = 'facture-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
