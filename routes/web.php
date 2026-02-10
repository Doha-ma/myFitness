<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'receptionist' => redirect()->route('receptionist.dashboard'),
            'coach' => redirect()->route('coach.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');

// Routes d'authentification
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes (shared across all roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Generic dashboard route
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'receptionist' => redirect()->route('receptionist.dashboard'),
            'coach' => redirect()->route('coach.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/members', [AdminController::class, 'membersIndex'])->name('members.index');
    Route::get('/members/create', [AdminController::class, 'membersCreate'])->name('members.create');
    Route::post('/members', [AdminController::class, 'membersStore'])->name('members.store');
    Route::get('/members/{member}/edit', [AdminController::class, 'membersEdit'])->name('members.edit');
    Route::put('/members/{member}', [AdminController::class, 'membersUpdate'])->name('members.update');
    Route::delete('/members/{member}', [AdminController::class, 'membersDestroy'])->name('members.destroy');
    
    Route::get('/receptionists', [AdminController::class, 'receptionistsIndex'])->name('receptionists.index');
    Route::get('/receptionists/create', [AdminController::class, 'receptionistsCreate'])->name('receptionists.create');
    Route::post('/receptionists', [AdminController::class, 'receptionistsStore'])->name('receptionists.store');
    Route::get('/receptionists/{receptionist}/edit', [AdminController::class, 'receptionistsEdit'])->name('receptionists.edit');
    Route::put('/receptionists/{receptionist}', [AdminController::class, 'receptionistsUpdate'])->name('receptionists.update');
    Route::delete('/receptionists/{receptionist}', [AdminController::class, 'receptionistsDestroy'])->name('receptionists.destroy');
    
    Route::get('/coaches', [AdminController::class, 'coachesIndex'])->name('coaches.index');
    Route::get('/coaches/create', [AdminController::class, 'coachesCreate'])->name('coaches.create');
    Route::post('/coaches', [AdminController::class, 'coachesStore'])->name('coaches.store');
    Route::get('/coaches/{coach}/edit', [AdminController::class, 'coachesEdit'])->name('coaches.edit');
    Route::put('/coaches/{coach}', [AdminController::class, 'coachesUpdate'])->name('coaches.update');
    Route::delete('/coaches/{coach}', [AdminController::class, 'coachesDestroy'])->name('coaches.destroy');
    
    Route::get('/subscription-types', [AdminController::class, 'subscriptionTypesIndex'])->name('subscription-types.index');
    Route::get('/subscription-types/create', [AdminController::class, 'subscriptionTypesCreate'])->name('subscription-types.create');
    Route::post('/subscription-types', [AdminController::class, 'subscriptionTypesStore'])->name('subscription-types.store');
    Route::get('/subscription-types/{subscriptionType}/edit', [AdminController::class, 'subscriptionTypesEdit'])->name('subscription-types.edit');
    Route::put('/subscription-types/{subscriptionType}', [AdminController::class, 'subscriptionTypesUpdate'])->name('subscription-types.update');
    Route::delete('/subscription-types/{subscriptionType}', [AdminController::class, 'subscriptionTypesDestroy'])->name('subscription-types.destroy');
    
    Route::get('/classes', [AdminController::class, 'classesIndex'])->name('classes.index');
    Route::get('/classes/create', [AdminController::class, 'classesCreate'])->name('classes.create');
    Route::post('/classes', [AdminController::class, 'classesStore'])->name('classes.store');
    Route::get('/classes/{classModel}', [AdminController::class, 'classesShow'])->name('classes.show');
    Route::get('/classes/{classModel}/edit', [AdminController::class, 'classesEdit'])->name('classes.edit');
    Route::put('/classes/{classModel}', [AdminController::class, 'classesUpdate'])->name('classes.update');
    Route::delete('/classes/{classModel}', [AdminController::class, 'classesDestroy'])->name('classes.destroy');
    
    Route::get('/classes/pending', [AdminController::class, 'pendingClasses'])->name('classes.pending');
    Route::post('/classes/{classModel}/approve', [AdminController::class, 'approveClass'])->name('classes.approve');
    Route::post('/classes/{classModel}/reject', [AdminController::class, 'rejectClass'])->name('classes.reject');
    
    // Payments (view only for admin)
    Route::get('/payments', [AdminController::class, 'paymentsIndex'])->name('payments.index');
    
    // Notifications
    Route::get('/notifications', [AdminController::class, 'notificationsIndex'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
});

// Receptionist Routes
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', [ReceptionistController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/members', [ReceptionistController::class, 'membersIndex'])->name('members.index');
    Route::get('/members/create', [ReceptionistController::class, 'membersCreate'])->name('members.create');
    Route::post('/members', [ReceptionistController::class, 'membersStore'])->name('members.store');
    Route::get('/members/{member}/edit', [ReceptionistController::class, 'membersEdit'])->name('members.edit');
    Route::put('/members/{member}', [ReceptionistController::class, 'membersUpdate'])->name('members.update');
    Route::delete('/members/{member}', [ReceptionistController::class, 'membersDestroy'])->name('members.destroy');
    
    Route::get('/payments', [ReceptionistController::class, 'paymentsIndex'])->name('payments.index');
    Route::get('/payments/create', [ReceptionistController::class, 'paymentsCreate'])->name('payments.create');
    Route::post('/payments', [ReceptionistController::class, 'paymentsStore'])->name('payments.store');
    // PDF invoice generation route
    Route::get('/payments/{payment}/invoice', [ReceptionistController::class, 'paymentsInvoice'])->name('payments.invoice');
});

// Coach Routes
Route::middleware(['auth', 'role:coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/classes', [CoachController::class, 'classesIndex'])->name('classes.index');
    Route::get('/classes/create', [CoachController::class, 'classesCreate'])->name('classes.create');
    Route::post('/classes', [CoachController::class, 'classesStore'])->name('classes.store');
    Route::get('/classes/{classModel}', [CoachController::class, 'classesShow'])->name('classes.show');
    Route::get('/classes/{classModel}/edit', [CoachController::class, 'classesEdit'])->name('classes.edit');
    Route::put('/classes/{classModel}', [CoachController::class, 'classesUpdate'])->name('classes.update');
    Route::delete('/classes/{classModel}', [CoachController::class, 'classesDestroy'])->name('classes.destroy');
    
    Route::post('/classes/{classModel}/schedules', [CoachController::class, 'schedulesStore'])->name('schedules.store');
    Route::delete('/classes/{classModel}/schedules/{schedule}', [CoachController::class, 'schedulesDestroy'])->name('schedules.destroy');
});