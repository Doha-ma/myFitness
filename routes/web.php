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
    Route::get('/staff', [AdminController::class, 'staffIndex'])->name('staff.index');
    Route::get('/staff/create', [AdminController::class, 'staffCreate'])->name('staff.create');
    Route::post('/staff', [AdminController::class, 'staffStore'])->name('staff.store');
    Route::get('/staff/{user}/edit', [AdminController::class, 'staffEdit'])->name('staff.edit');
    Route::put('/staff/{user}', [AdminController::class, 'staffUpdate'])->name('staff.update');
    Route::delete('/staff/{user}', [AdminController::class, 'staffDestroy'])->name('staff.destroy');
    
    // Subscription Types Management
    Route::resource('subscription-types', \App\Http\Controllers\Admin\SubscriptionTypeController::class)->except(['show']);
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