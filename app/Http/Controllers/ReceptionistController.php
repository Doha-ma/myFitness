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
use App\Services\PaymentEmailService;
use App\Mail\PaymentConfirmationEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        $totalMembers = Member::count();
        $totalPaymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $recentMembers = Member::with('latestSubscriptionPayment.subscriptionType')->latest()->take(5)->get();
        $activeSubscriptionsCount = Member::whereDate('subscription_end_date', '>=', today())->count();

        $approvedClasses = ClassModel::with(['coach', 'schedules'])
            ->withCount('enrollments')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        $activeSubscriptions = Member::with('latestSubscriptionPayment.subscriptionType')
            ->whereDate('subscription_end_date', '>=', today())
            ->orderBy('subscription_end_date')
            ->take(10)
            ->get();

        return view('receptionist.dashboard', compact(
            'totalMembers',
            'totalPaymentsToday',
            'recentMembers',
            'activeSubscriptionsCount',
            'approvedClasses',
            'activeSubscriptions'
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

        $selectedClassIds = array_values(array_unique(array_map(
            'intval',
            $validated['classes'] ?? []
        )));

        $this->assertClassesHaveAvailablePlaces($selectedClassIds);

        $member = Member::create($validated);

        if (!empty($selectedClassIds)) {
            $enrollmentData = [];

            foreach ($selectedClassIds as $classId) {
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
        $member->load([
            'latestSubscriptionPayment.subscriptionType',
            'classes',
        ]);

        $classes = ClassModel::with('coach')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        $subscriptionTypes = SubscriptionType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('receptionist.members.edit', compact('member', 'subscriptionTypes', 'classes'));
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
            'classes' => 'nullable|array',
            'classes.*' => [
                Rule::exists('classes', 'id')->where(function ($query) {
                    $query->where('status', 'approved');
                }),
            ],
        ]);

        $selectedClassIds = array_values(array_unique(array_map(
            'intval',
            $validated['classes'] ?? []
        )));

        $currentClassIds = $member->classes()
            ->pluck('classes.id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $classIdsToAttach = array_values(array_diff($selectedClassIds, $currentClassIds));
        $this->assertClassesHaveAvailablePlaces($classIdsToAttach);

        $member->update(collect($validated)->except('classes')->all());

        $classIdsToDetach = array_diff($currentClassIds, $selectedClassIds);
        if (!empty($classIdsToDetach)) {
            $member->classes()->detach($classIdsToDetach);
        }

        if (!empty($classIdsToAttach)) {
            $enrollmentData = [];
            foreach ($classIdsToAttach as $classId) {
                $enrollmentData[$classId] = [
                    'enrollment_date' => $validated['join_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $member->classes()->attach($enrollmentData);
        }

        $member->syncMembershipStatusFromSubscription();
        $member->save();

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Le membre a ete mis a jour avec succes.');
    }

    public function membersUpdateSubscription(Request $request, Member $member)
    {
        $validated = $request->validate([
            'subscription_type_id' => [
                'required',
                Rule::exists('subscription_types', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

        $subscriptionType = SubscriptionType::findOrFail((int) $validated['subscription_type_id']);

        $payment = Payment::create([
            'member_id' => $member->id,
            'subscription_type_id' => $subscriptionType->id,
            'receptionist_id' => auth()->id(),
            'amount' => $subscriptionType->final_price,
            'payment_date' => $validated['payment_date'],
            'method' => $validated['method'],
            'notes' => $validated['notes'] ?? 'Mise a jour abonnement depuis fiche membre.',
        ]);

        $member->subscription_end_date = $payment->payment_date
            ->copy()
            ->addDays((int) $subscriptionType->duration_days);
        $member->status = 'active';
        $member->save();

        return back()->with('success', 'L abonnement du membre a ete mis a jour avec succes.');
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
            'send_email' => 'nullable|boolean',
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

        // Envoyer l'email de reçu au client si coché
        $sendEmail = $request->boolean('send_email', true);
        $emailResult = ['success' => false, 'message' => ''];
        
        if ($sendEmail && !empty($payment->member->email)) {
            try {
                \Log::info('Tentative envoi email pour paiement ID: ' . $payment->id);
                \Log::info('Email client : ' . $payment->member->email);
                
                // Préparer les données pour l'email
                $emailData = [
                    'member' => $payment->member,
                    'payment' => $payment,
                    'receiptNumber' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                    'receiptUrl' => route('receptionist.payments.receipt', $payment),
                ];
                
                // Envoyer l'email avec la classe Mailable
                Mail::to($payment->member->email)->send(new PaymentConfirmationEmail($emailData));
                
                \Log::info('Email envoyé avec succès à : ' . $payment->member->email);
                
                // Mettre à jour le statut d'email dans la base
                $payment->update([
                    'email_sent_at' => now(),
                    'email_status' => 'sent'
                ]);
                
                $emailResult = [
                    'success' => true,
                    'message' => 'Email de confirmation envoyé au client avec succès.'
                ];
                
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi d\'email au client: ' . $e->getMessage());
                
                // Mettre à jour le statut d'échec
                $payment->update([
                    'email_status' => 'failed',
                    'email_error' => $e->getMessage()
                ]);
                
                $emailResult = [
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi d\'email: ' . $e->getMessage()
                ];
            }
        } elseif ($sendEmail && empty($payment->member->email)) {
            \Log::warning('Membre sans email - ID: ' . $payment->member->id);
            
            // Mettre à jour le statut d'échec
            $payment->update([
                'email_status' => 'failed',
                'email_error' => 'Email du membre manquant'
            ]);
            
            $emailResult = [
                'success' => false,
                'message' => 'Email non envoyé: le membre n\'a pas d\'adresse email.'
            ];
        } else {
            // Utilisateur a choisi de ne pas envoyer d'email
            $emailResult = [
                'success' => true,
                'message' => 'Email non envoyé selon la demande.'
            ];
        }

        // Construire le message de succès
        $successMessage = 'Le paiement a été enregistré avec succès.';
        if ($emailResult['success']) {
            $successMessage .= ' ' . $emailResult['message'];
        }

        return redirect()->route('receptionist.payments.index')
            ->with('success', $successMessage);
    }

    /**
     * Display payment receipt
     */
    public function paymentsReceipt(Payment $payment)
    {
        $payment->load(['member', 'receptionist', 'subscriptionType']);
        
        return view('payments.receipt', compact('payment'));
    }

    /**
     * Resend payment confirmation email
     */
    public function paymentsResendEmail(Payment $payment)
    {
        try {
            if (empty($payment->member->email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le membre n\'a pas d\'adresse email.'
                ]);
            }

            // Préparer les données pour l'email
            $emailData = [
                'member' => $payment->member,
                'payment' => $payment,
                'receiptNumber' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'receiptUrl' => route('receptionist.payments.receipt', $payment),
            ];
            
            // Envoyer l'email
            Mail::to($payment->member->email)->send(new PaymentConfirmationEmail($emailData));
            
            // Mettre à jour le statut d'email dans la base
            $payment->update([
                'email_sent_at' => now(),
                'email_status' => 'sent',
                'email_error' => null
            ]);
            
            \Log::info('Email de paiement renvoyé avec succès', [
                'payment_id' => $payment->id,
                'member_email' => $payment->member->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Email envoyé avec succès.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du renvoi d\'email de paiement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            
            // Mettre à jour le statut d'échec
            $payment->update([
                'email_status' => 'failed',
                'email_error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi: ' . $e->getMessage()
            ]);
        }
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

    private function assertClassesHaveAvailablePlaces(array $classIds): void
    {
        if (empty($classIds)) {
            return;
        }

        $fullClasses = ClassModel::query()
            ->withCount('enrollments')
            ->whereIn('id', $classIds)
            ->get()
            ->filter(fn (ClassModel $classModel) => (int) $classModel->enrollments_count >= (int) $classModel->capacity)
            ->pluck('name')
            ->all();

        if (!empty($fullClasses)) {
            throw ValidationException::withMessages([
                'classes' => "Impossible d'ajouter ce membre: plus de places disponibles dans " . implode(', ', $fullClasses) . '.',
            ]);
        }
    }
}
