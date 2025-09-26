<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GanttController;
use App\Http\Controllers\ActualProgressController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProcessManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'));
Route::get('/check-role', fn() => 'Current User Role: ' . (auth()->user()?->role ?? 'Guest'));

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    // Login / Logout
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Roles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | GA Staff Dashboard & CRUD
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:GA Staff')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Application Management (CRUD)
        Route::resource('applications', ApplicationController::class);

        // Actual Progress Entry (CRUD)
        Route::prefix('actual-entry')->name('actual.')->group(function () {
            Route::get('/', [ActualProgressController::class, 'index'])->name('index'); // /actual-entry
            Route::get('/{id}/edit', [ActualProgressController::class, 'edit'])->name('edit'); // /actual-entry/{id}/edit
            Route::put('/{id}', [ActualProgressController::class, 'update'])->name('update'); // /actual-entry/{id}
        });

        // Process Management (CRUD)
        Route::prefix('process-management')->name('process.')->group(function () {
            Route::get('/', [ProcessManagementController::class, 'index'])->name('index');
            Route::get('/create', [ProcessManagementController::class, 'create'])->name('create');
            Route::post('/', [ProcessManagementController::class, 'store'])->name('store');
            Route::get('/{process}/edit', [ProcessManagementController::class, 'edit'])->name('edit');
            Route::put('/{process}', [ProcessManagementController::class, 'update'])->name('update');
            Route::delete('/{process}', [ProcessManagementController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Gantt Chart (Read-Only for GA Staff, Admin Expatriate, Expatriate)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role:GA Staff,Admin Expatriate,Expatriate'])->group(function () {
        Route::get('/gantt', [GanttController::class, 'index'])->name('gantt.index');
        
    });


    
    
    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});
