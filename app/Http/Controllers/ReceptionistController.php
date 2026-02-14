<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Member;
use App\Models\Payment;
use App\Models\SubscriptionType;
use App\Models\User;
use App\Notifications\NewMemberRegistered;
use App\Notifications\PaymentValidated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        $totalMembers = Member::count();
        $totalPaymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $recentMembers = Member::with('latestSubscriptionPayment.subscriptionType')->latest()->take(5)->get();

        return view('receptionist.dashboard', compact(
            'totalMembers',
            'totalPaymentsToday',
            'recentMembers'
        ));
    }

    public function membersIndex(Request $request)
    {
        $query = Member::with([
            'classes',
            'latestSubscriptionPayment.subscriptionType',
        ]);

        if ($request->filled('class_id')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->integer('class_id'));
            });
        }

        if ($request->filled('subscription_type_id')) {
            $query->whereHas('latestSubscriptionPayment', function ($q) use ($request) {
                $q->where('subscription_type_id', $request->integer('subscription_type_id'));
            });
        }

        if ($request->filled('subscription_status')) {
            if ($request->subscription_status === 'expired') {
                $query->whereDate('subscription_end_date', '<', today());
            }

            if ($request->subscription_status === 'active') {
                $query->whereDate('subscription_end_date', '>=', today());
            }
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        $classes = ClassModel::with('coach')
            ->where('status', 'approved')
            ->get();

        $subscriptionTypes = SubscriptionType::where('is_active', true)->get();

        return view('receptionist.members.index', compact('members', 'classes', 'subscriptionTypes'));
    }

    public function membersCreate()
    {
        $classes = ClassModel::with('coach')
            ->where('status', 'approved')
            ->get();

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
            'classes' => 'nullable|array',
            'classes.*' => [
                Rule::exists('classes', 'id')->where(function ($query) {
                    $query->where('status', 'approved');
                }),
            ],
        ]);

        $member = Member::create($validated);

        if ($request->has('classes') && is_array($request->classes) && count($request->classes) > 0) {
            $enrollmentData = [];

            foreach ($request->classes as $classId) {
                $exists = Enrollment::where('member_id', $member->id)
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

            if (!empty($enrollmentData)) {
                $member->classes()->attach($enrollmentData);
            }
        }

        User::where('role', 'admin')->get()->each(function (User $admin) use ($member) {
            $admin->notify(new NewMemberRegistered($member, auth()->user()));
        });

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Le membre a ete ajoute avec succes.');
    }

    public function membersEdit(Member $member)
    {
        $member->load('latestSubscriptionPayment.subscriptionType');

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
            'subscription_end_date' => 'nullable|date',
        ]);

        $member->update($validated);
        $member->syncMembershipStatusFromSubscription();
        $member->save();

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Le membre a ete mis a jour avec succes.');
    }

    public function renewSubscription(Request $request, Member $member)
    {
        $request->validate([
            'duration_days' => 'nullable|integer|min:1|max:365',
        ]);

        $latestSubscriptionPayment = $member->latestSubscriptionPayment()->with('subscriptionType')->first();
        $durationDays = (int) ($request->input('duration_days')
            ?? optional($latestSubscriptionPayment?->subscriptionType)->duration_days
            ?? 30);

        $baseDate = $member->subscription_end_date && $member->subscription_end_date->isFuture()
            ? $member->subscription_end_date->copy()
            : today();

        $member->subscription_end_date = $baseDate->addDays($durationDays);
        $member->status = 'active';
        $member->save();

        return back()->with('success', "Abonnement renouvele pour {$durationDays} jours.");
    }

    public function updateSubscriptionEndDate(Request $request, Member $member)
    {
        $validated = $request->validate([
            'subscription_end_date' => 'required|date',
        ]);

        $member->subscription_end_date = $validated['subscription_end_date'];
        $member->syncMembershipStatusFromSubscription();
        $member->save();

        return back()->with('success', 'La date de fin de l abonnement a ete mise a jour.');
    }

    public function markAsPaid(Member $member)
    {
        if (!$member->subscription_end_date || $member->subscription_end_date->lt(today())) {
            $member->subscription_end_date = today()->addDays(30);
        }

        $member->status = 'active';
        $member->save();

        return back()->with('success', 'Le membre est marque comme paye.');
    }

    /**
     * Delete a member.
     */
    public function membersDestroy(Member $member)
    {
        try {
            $paymentsCount = $member->payments()->count();

            if ($paymentsCount > 0) {
                return redirect()->route('receptionist.members.index')
                    ->with('error', "Suppression impossible : ce membre possede {$paymentsCount} paiement(s).");
            }

            $member->classes()->detach();
            $member->delete();

            return redirect()->route('receptionist.members.index')
                ->with('success', 'Le membre a ete supprime avec succes.');
        } catch (\Exception $e) {
            \Log::error('Error deleting member: ' . $e->getMessage());

            return redirect()->route('receptionist.members.index')
                ->with('error', 'Une erreur est survenue pendant la suppression.');
        }
    }

    public function paymentsIndex()
    {
        $payments = Payment::with(['member', 'receptionist', 'subscriptionType'])
            ->latest()
            ->paginate(15);

        return view('receptionist.payments.index', compact('payments'));
    }

    public function paymentsCreate(Request $request)
    {
        $members = Member::where('status', 'active')->get();
        $subscriptionTypes = SubscriptionType::where('is_active', true)->get();
        $selectedMemberId = $request->integer('member_id');

        return view('receptionist.payments.create', compact('members', 'subscriptionTypes', 'selectedMemberId'));
    }

    public function paymentsStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'subscription_type_id' => 'nullable|exists:subscription_types,id',
            'amount' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

        if ($request->filled('subscription_type_id')) {
            $subscriptionType = SubscriptionType::findOrFail($request->subscription_type_id);
            $validated['amount'] = $subscriptionType->final_price;
        } else {
            $request->validate([
                'amount' => 'required|numeric|min:0',
            ]);
        }

        $payment = Payment::create([
            ...$validated,
            'receptionist_id' => auth()->id(),
        ]);

        if (!empty($payment->subscription_type_id) && $payment->subscriptionType) {
            $member = $payment->member;
            $member->subscription_end_date = $payment->payment_date
                ->copy()
                ->addDays((int) $payment->subscriptionType->duration_days);
            $member->status = 'active';
            $member->save();
        }

        User::where('role', 'admin')->get()->each(function (User $admin) use ($payment) {
            $admin->notify(new PaymentValidated($payment, auth()->user()));
        });

        return redirect()->route('receptionist.payments.index')
            ->with('success', 'Le paiement a ete enregistre avec succes.');
    }

    /**
     * Generate PDF invoice for a payment.
     */
    public function paymentsInvoice(Payment $payment)
    {
        $payment->load(['member', 'receptionist']);

        $pdf = Pdf::loadView('pdf.payment-invoice', compact('payment'));

        $filename = 'facture-' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}

