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

    public function membersIndex()
    {
        $members = Member::latest()->paginate(15);
        return view('receptionist.members.index', compact('members'));
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

    public function paymentsIndex()
    {
        $payments = Payment::with(['member', 'receptionist'])
            ->latest()
            ->paginate(15);

        return view('receptionist.payments.index', compact('payments'));
    }

    public function paymentsCreate()
    {
        $members = Member::where('status', 'active')->get();
        return view('receptionist.payments.create', compact('members'));
    }

    public function paymentsStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

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
