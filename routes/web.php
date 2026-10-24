<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PcUnitController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CoinInsertController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LockScreenController;
use App\Http\Middleware\AdminMiddleware;

// Guest routes (login only — registration is admin-only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pc-units', PcUnitController::class)->names('pc-units');
    Route::resource('sessions', SessionController::class)->names('sessions');
    Route::resource('transactions', TransactionController::class)->names('transactions');
    Route::resource('coin-inserts', CoinInsertController::class)->names('coin-inserts');
    Route::resource('settings', SettingsController::class)->names('settings');
    Route::resource('rates', RatesController::class)->names('rates');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/lock', [LockScreenController::class, 'index'])->name('lock');
    Route::put('/lock', [LockScreenController::class, 'update'])->name('lock.update');
    Route::get('/lock/preview', [LockScreenController::class, 'preview'])->name('lock.preview');

    // Admin-only: user registration (create new system accounts)
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });
});
