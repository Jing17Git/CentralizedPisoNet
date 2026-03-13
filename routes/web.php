<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EarningsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PcUnitController;
use App\Http\Controllers\SessionController;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CoinInsertController;

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - requires authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard (admin dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin dashboard 
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // API route for real-time dashboard data (JSON)
    Route::get('/api/dashboard/realtime', [DashboardController::class, 'realtimeData'])->name('dashboard.realtime');
    
    // API route for dashboard dataset (system summary)
    Route::get('/api/dashboard/dataset', [DashboardController::class, 'getDataset'])->name('dashboard.dataset');
    
    // Admin routes
    Route::group([], function () {
        // Password update route
        Route::post('/admin/password', [AuthController::class, 'updatePassword'])->name('admin.password.update');
        
        // Profile routes
        Route::get('/admin/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('admin.profile.show');
        Route::get('/admin/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/admin/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('admin.profile.update');
        
        // Earnings routes
        Route::get('/admin/earnings', [EarningsController::class, 'index'])->name('earnings.index');
        Route::get('/admin/earnings/export/pdf', [EarningsController::class, 'exportPDF'])->name('earnings.export.pdf');
        Route::get('/admin/earnings/export/excel', [EarningsController::class, 'exportExcel'])->name('earnings.export.excel');
        
        // PC Units routes
        Route::get('/admin/pc-units', [PcUnitController::class, 'index'])->name('pcunits.index');
        Route::post('/admin/pc-units', [PcUnitController::class, 'store'])->name('pcunits.store');
        Route::put('/admin/pc-units/{pcUnit}', [PcUnitController::class, 'update'])->name('pcunits.update');
        Route::delete('/admin/pc-units/{pcUnit}', [PcUnitController::class, 'destroy'])->name('pcunits.destroy');
        Route::post('/admin/pc-units/{pcUnit}/toggle-active', [PcUnitController::class, 'toggleActive'])->name('pcunits.toggleActive');
        Route::post('/admin/pc-units/{pcUnit}/status', [PcUnitController::class, 'updateStatus'])->name('pcunits.updateStatus');
        
        // Session Monitoring routes
        Route::get('/admin/sessions', [SessionController::class, 'index'])->name('sessions.index');
        Route::post('/admin/sessions', [SessionController::class, 'store'])->name('sessions.store');
        Route::post('/admin/sessions/{session}/end', [SessionController::class, 'endSession'])->name('sessions.end');
        Route::post('/admin/sessions/{session}/extend', [SessionController::class, 'extendSession'])->name('sessions.extend');
        Route::get('/api/sessions/active', [SessionController::class, 'getActiveSessions'])->name('sessions.api.active');
        
        // Transaction routes
        Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/admin/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::post('/admin/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
        Route::get('/admin/transactions/export/pdf', [TransactionController::class, 'exportPDF'])->name('transactions.export.pdf');
        Route::get('/admin/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
        
        // Coin Insert routes
        Route::get('/admin/coin-inserts', [CoinInsertController::class, 'index'])->name('coininserts.index');
        Route::post('/admin/coin-inserts', [CoinInsertController::class, 'store'])->name('coininserts.store');
        Route::get('/admin/coin-inserts/export/pdf', [CoinInsertController::class, 'exportPDF'])->name('coininserts.export.pdf');
        Route::get('/admin/coin-inserts/export/excel', [CoinInsertController::class, 'exportExcel'])->name('coininserts.export.excel');
    });
});

// Redirect root to dashboard (will require auth later)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

