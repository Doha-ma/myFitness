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
     * Display receptionists management page
     */
    public function receptionistsIndex()
    {
        $receptionists = User::where('role', 'receptionist')
            ->withCount('paymentsAsReceptionist')
            ->latest()
            ->paginate(20);

        return view('admin.receptionists.index', compact('receptionists'));
    }

    /**
     * Show form for creating a new receptionist
     */
    public function receptionistsCreate()
    {
        return view('admin.receptionists.create');
    }

    /**
     * Store a newly created receptionist
     */
    public function receptionistsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom du réceptionniste est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'receptionist',
        ]);

        return redirect()->route('admin.receptionists.index')
            ->with('success', 'Réceptionniste créé avec succès!');
    }

    /**
     * Show form for editing a receptionist
     */
    public function receptionistsEdit(User $receptionist)
    {
        return view('admin.receptionists.edit', compact('receptionist'));
    }

    /**
     * Update the specified receptionist
     */
    public function receptionistsUpdate(Request $request, User $receptionist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $receptionist->id,
            'role' => 'required|in:receptionist',
        ]);

        $receptionist->update($validated);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $receptionist->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.receptionists.index')
            ->with('success', 'Réceptionniste mis à jour avec succès!');
    }

    /**
     * Remove the specified receptionist
     */
    public function receptionistsDestroy(User $receptionist)
    {
        // Check for related data before deletion
        $paymentsCount = $receptionist->paymentsAsReceptionist()->count();
        if ($paymentsCount > 0) {
            return redirect()->route('admin.receptionists.index')
                ->with('error', 'Impossible de supprimer ce réceptionniste car il a des paiements enregistrés.');
        }

        $receptionist->delete();

        return redirect()->route('admin.receptionists.index')
            ->with('success', 'Réceptionniste supprimé avec succès!');
    }

    /**
     * Remove the specified member
     */
    public function membersDestroy(Member $member)
    {
        // Check for related data before deletion
        $paymentsCount = $member->payments()->count();
        if ($paymentsCount > 0) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Impossible de supprimer ce membre car il a des paiements enregistrés.');
        }

        // Detach member from all classes
        $member->classes()->detach();

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre supprimé avec succès!');
    }

    /**
     * Remove the specified coach
     */
    public function coachesDestroy(User $coach)
    {
        // Check for related data before deletion
        $classesCount = $coach->classesAsCoach()->count();
        if ($classesCount > 0) {
            return redirect()->route('admin.coaches.index')
                ->with('error', 'Impossible de supprimer ce coach car il a des cours associés.');
        }

        $coach->delete();

        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach supprimé avec succès!');
    }

    /**
     * Show the form for creating a new coach
     */
    public function coachesCreate()
    {
        return view('admin.coaches.create');
    }

    /**
     * Store a newly created coach in storage
     */
    public function coachesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom du coach est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ]);

        // Create coach with role
        $coach = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'coach',
        ]);

        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach créé avec succès!');
    }

    /**
     * Show the form for editing the specified coach
     */
    public function coachesEdit(User $coach)
    {
        return view('admin.coaches.edit', compact('coach'));
    }

    /**
     * Update the specified coach in storage
     */
    public function coachesUpdate(Request $request, User $coach)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $coach->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Le nom du coach est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ]);

        // Update coach data
        $coach->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $coach->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach mis à jour avec succès!');
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

    /**
     * Display all members for admin
     */
    public function membersIndex()
    {
        $members = Member::withCount('enrollments')
            ->latest()
            ->paginate(20);

        return view('admin.members.index', compact('members'));
    }

    /**
     * Display all coaches for admin
     */
    public function coachesIndex()
    {
        $coaches = User::where('role', 'coach')
            ->withCount('classesAsCoach')
            ->latest()
            ->paginate(20);

        return view('admin.coaches.index', compact('coaches'));
    }

    /**
     * Display all subscription types for admin
     */
    public function subscriptionTypesIndex()
    {
        $subscriptionTypes = SubscriptionType::latest()->paginate(20);

        return view('admin.subscription-types.index', compact('subscriptionTypes'));
    }

    /**
     * Show the form for creating a new subscription type
     */
    public function subscriptionTypesCreate()
    {
        return view('admin.subscription-types.create');
    }

    /**
     * Store a newly created subscription type in storage
     */
    public function subscriptionTypesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Le nom du type d\'abonnement est obligatoire',
            'base_price.required' => 'Le prix de base est obligatoire',
            'base_price.numeric' => 'Le prix de base doit être un nombre',
            'discount_type.required' => 'Le type de réduction est obligatoire',
            'duration_days.required' => 'La durée est obligatoire',
            'duration_days.integer' => 'La durée doit être un nombre entier',
        ]);

        SubscriptionType::create($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement créé avec succès!');
    }

    /**
     * Show the form for editing the specified subscription type
     */
    public function subscriptionTypesEdit(SubscriptionType $subscriptionType)
    {
        return view('admin.subscription-types.edit', compact('subscriptionType'));
    }

    /**
     * Update the specified subscription type in storage
     */
    public function subscriptionTypesUpdate(Request $request, SubscriptionType $subscriptionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Le nom du type d\'abonnement est obligatoire',
            'base_price.required' => 'Le prix de base est obligatoire',
            'base_price.numeric' => 'Le prix de base doit être un nombre',
            'discount_type.required' => 'Le type de réduction est obligatoire',
            'duration_days.required' => 'La durée est obligatoire',
            'duration_days.integer' => 'La durée doit être un nombre entier',
        ]);

        $subscriptionType->update($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement mis à jour avec succès!');
    }

    /**
     * Remove the specified subscription type from storage
     */
    public function subscriptionTypesDestroy(SubscriptionType $subscriptionType)
    {
        // Check for related payments before deletion
        $paymentsCount = $subscriptionType->payments()->count();
        if ($paymentsCount > 0) {
            return redirect()->route('admin.subscription-types.index')
                ->with('error', 'Impossible de supprimer ce type d\'abonnement car il a des paiements associés.');
        }

        $subscriptionType->delete();

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement supprimé avec succès!');
    }

    /**
     * Display all classes for admin
     */
    public function classesIndex()
    {
        $classes = ClassModel::with(['coach', 'schedules'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(20);

        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Display class details
     */
    public function classesShow($classModel)
    {
        // Load specific class with relationships
        $class = ClassModel::with(['coach', 'schedules', 'enrollments.member'])
            ->findOrFail($classModel);

        return view('admin.classes.show', compact('class'));
    }

    /**
     * Show the form for creating a new class
     */
    public function classesCreate()
    {
        $coaches = User::where('role', 'coach')->get();
        return view('admin.classes.create', compact('coaches'));
    }

    /**
     * Store a newly created class in storage
     */
    public function classesStore(Request $request)
    {
        // Validation rules for class creation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'coach_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
            'duration' => 'required|integer|min:15|max:480',
            'status' => 'nullable|in:pending,approved,rejected'
        ], [
            'name.required' => 'Le nom du cours est obligatoire',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'coach_id.required' => 'Le coach est obligatoire',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas',
            'capacity.required' => 'La capacité est obligatoire',
            'capacity.min' => 'La capacité doit être d\'au moins 1 personne',
            'capacity.max' => 'La capacité ne peut pas dépasser 100 personnes',
            'duration.required' => 'La durée est obligatoire',
            'duration.min' => 'La durée doit être d\'au moins 15 minutes',
            'duration.max' => 'La durée ne peut pas dépasser 8 heures (480 minutes)',
        ]);

        // Create class with validated data
        $class = ClassModel::create([
            ...$validated,
            'status' => $validated['status'] ?? 'pending', // Default to pending if not specified
        ]);

        // Send notification to admin if class was created by coach (handled in CoachController)
        // For admin-created classes, no notification needed as admin is the creator

        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Cours créé avec succès!');
    }

    /**
     * Show the form for editing the specified class
     */
    public function classesEdit(ClassModel $classModel)
    {
        $coaches = User::where('role', 'coach')->get();
        return view('admin.classes.edit', compact('classModel', 'coaches'));
    }

    /**
     * Update the specified class in storage
     */
    public function classesUpdate(Request $request, ClassModel $classModel)
    {
        // Validation rules for class update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'coach_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
            'duration' => 'required|integer|min:15|max:480',
            'status' => 'required|in:pending,approved,rejected'
        ], [
            'name.required' => 'Le nom du cours est obligatoire',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'coach_id.required' => 'Le coach est obligatoire',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas',
            'capacity.required' => 'La capacité est obligatoire',
            'capacity.min' => 'La capacité doit être d\'au moins 1 personne',
            'capacity.max' => 'La capacité ne peut pas dépasser 100 personnes',
            'duration.required' => 'La durée est obligatoire',
            'duration.min' => 'La durée doit être d\'au moins 15 minutes',
            'duration.max' => 'La durée ne peut pas dépasser 8 heures (480 minutes)',
            'status.required' => 'Le statut est obligatoire',
            'status.in' => 'Le statut doit être valide (pending, approved, rejected)',
        ]);

        // Update class with validated data
        $classModel->update($validated);

        return redirect()->route('admin.classes.show', $classModel)
            ->with('success', 'Cours mis à jour avec succès!');
    }

    /**
     * Remove the specified class from storage
     */
    public function classesDestroy(ClassModel $classModel)
    {
        try {
            // Check for related data before deletion
            $enrollmentsCount = $classModel->enrollments()->count();
            $schedulesCount = $classModel->schedules()->count();
            
            if ($enrollmentsCount > 0) {
                return redirect()->route('admin.classes.index')
                    ->with('error', 'Impossible de supprimer ce cours car il contient ' . $enrollmentsCount . ' inscription(s).');
            }

            // Delete schedules first (foreign key constraint)
            $classModel->schedules()->delete();
            
            // Delete the class
            $classModel->delete();
            
            return redirect()->route('admin.classes.index')
                ->with('success', 'Cours supprimé avec succès!');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting class: ' . $e->getMessage());
            return redirect()->route('admin.classes.index')
                ->with('error', 'Erreur lors de la suppression. Veuillez réessayer.');
        }
    }

    /**
     * Display all notifications for admin
     */
    public function notificationsIndex()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markNotificationAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Display pending courses for approval
     */
    public function pendingClasses()
    {
        try {
            $pendingClasses = ClassModel::with(['coach', 'schedules'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(15);

            return view('admin.classes.pending', compact('pendingClasses'));
        } catch (\Exception $e) {
            \Log::error('Error in pendingClasses: ' . $e->getMessage());
            
            // En cas d'erreur, rediriger vers le dashboard admin
            return redirect()->route('admin.dashboard')
                ->with('error', 'Erreur lors du chargement des cours en attente.');
        }
    }

    /**
     * Approve a pending course
     */
    public function approveClass(ClassModel $classModel)
    {
        try {
            // Vérifier que le cours est bien en attente
            if ($classModel->status !== 'pending') {
                return redirect()->route('admin.classes.pending')
                    ->with('error', 'Ce cours ne peut pas être approuvé car il n\'est pas en attente.');
            }

            // Approuver le cours
            $classModel->update(['status' => 'approved']);

            // TODO: Envoyer notification au coach (désactivé pour éviter les erreurs)
            // $classModel->coach->notify(new \App\Notifications\CourseApproved($classModel));

            return redirect()->route('admin.classes.pending')
                ->with('success', 'Le cours "' . $classModel->name . '" a été approuvé avec succès!');
                
        } catch (\Exception $e) {
            \Log::error('Error approving class: ' . $e->getMessage());
            return redirect()->route('admin.classes.pending')
                ->with('error', 'Erreur lors de l\'approbation. Veuillez réessayer.');
        }
    }

    /**
     * Reject a pending course
     */
    public function rejectClass(ClassModel $classModel, Request $request)
    {
        try {
            // Vérifier que le cours est bien en attente
            if ($classModel->status !== 'pending') {
                return redirect()->route('admin.classes.pending')
                    ->with('error', 'Ce cours ne peut pas être rejeté car il n\'est pas en attente.');
            }

            // Validation de la raison de rejet
            $validated = $request->validate([
                'rejection_reason' => 'nullable|string|max:500'
            ], [
                'rejection_reason.max' => 'La raison du rejet ne peut pas dépasser 500 caractères.'
            ]);

            // Rejeter le cours avec la raison
            $classModel->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'] ?? null
            ]);

            // TODO: Envoyer notification au coach (désactivé pour éviter les erreurs)
            // $classModel->coach->notify(new \App\Notifications\CourseRejected($classModel, $validated['rejection_reason'] ?? null));

            return redirect()->route('admin.classes.pending')
                ->with('success', 'Le cours "' . $classModel->name . '" a été rejeté avec succès!');
                
        } catch (\Exception $e) {
            \Log::error('Error rejecting class: ' . $e->getMessage());
            return redirect()->route('admin.classes.pending')
                ->with('error', 'Erreur lors du rejet. Veuillez réessayer.');
        }
    }

    /**
     * Display all payments for admin
     */
    public function paymentsIndex()
    {
        $payments = Payment::with(['member', 'receptionist'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }
}