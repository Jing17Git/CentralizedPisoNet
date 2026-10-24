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
use App\Http\Controllers\CoinTestController;

// Public routes (no auth needed)
Route::get('/rates/api', [RatesController::class, 'api'])->name('rates.api');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Must be before resource() to avoid being captured by {pc_unit} wildcard
    Route::get('/pc-units/realtime-status', [PcUnitController::class, 'realtimeStatus'])->name('pc-units.realtime-status');
    Route::resource('pc-units', PcUnitController::class)->names('pc-units');
    Route::resource('sessions', SessionController::class)->names('sessions');
    Route::resource('transactions', TransactionController::class)->names('transactions');
    Route::resource('coin-inserts', CoinInsertController::class)->names('coin-inserts');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/rates', [RatesController::class, 'index'])->name('rates.index');
    Route::post('/rates/update', [RatesController::class, 'update'])->name('rates.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/lock', [LockScreenController::class, 'index'])->name('lock.index');
    Route::put('/lock', [LockScreenController::class, 'update'])->name('lock.update');

    Route::post('/coin-test/open-slot/{machineCode}', [CoinTestController::class, 'openSlot'])->name('coin-test.open-slot');
    Route::post('/coin-test/close-slot/{machineCode}', [CoinTestController::class, 'closeSlot'])->name('coin-test.close-slot');
    Route::get('/coin-test', [CoinTestController::class, 'index'])->name('coin-test.index');
    Route::get('/coin-test/session/{machineCode}', [CoinTestController::class, 'session'])->name('coin-test.session');
    Route::post('/coin-test/reset/{machineCode}', [CoinTestController::class, 'reset'])->name('coin-test.reset');
    Route::post('/coin-test/done/{machineCode}', [CoinTestController::class, 'done'])->name('coin-test.done');
    Route::post('/coin-test/cancel/{machineCode}', [CoinTestController::class, 'cancel'])->name('coin-test.cancel');
});
