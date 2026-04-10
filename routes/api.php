<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MachineApiController;
use App\Http\Controllers\Api\SessionApiController;
use App\Http\Controllers\Api\CoinApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (with token authentication)
Route::middleware(['api.token'])->group(function () {
    
    // Machine Management API
    Route::prefix('machines')->group(function () {
        Route::get('/', [MachineApiController::class, 'index']);
        Route::get('/{machineCode}', [MachineApiController::class, 'show']);
        Route::post('/{machineCode}/status', [MachineApiController::class, 'updateStatus']);
        Route::post('/{machineCode}/heartbeat', [MachineApiController::class, 'heartbeat']);
    });
    
    // Session Management API
    Route::prefix('sessions')->group(function () {
        Route::post('/start', [SessionApiController::class, 'start']);
        Route::post('/{sessionId}/end', [SessionApiController::class, 'end']);
        Route::post('/{sessionId}/extend', [SessionApiController::class, 'extend']);
        Route::get('/{sessionId}', [SessionApiController::class, 'show']);
        Route::get('/machine/{machineCode}', [SessionApiController::class, 'getByMachine']);
    });
    
    // Coin Insert API
    Route::prefix('coins')->group(function () {
        Route::post('/insert', [CoinApiController::class, 'insert']);
        Route::get('/machine/{machineCode}/today', [CoinApiController::class, 'getTodayCoins']);
    });
    
    // System Status API
    Route::get('/system/status', function () {
        return response()->json([
            'success' => true,
            'status' => 'online',
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0.0'
        ]);
    });
});
