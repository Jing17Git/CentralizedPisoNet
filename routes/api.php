<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MachineApiController;
use App\Http\Controllers\Api\SessionApiController;
use App\Http\Controllers\Api\CoinApiController;

Route::middleware(['api.token', 'throttle:60,1'])->group(function () {
    // Machine routes
    Route::get('/machines', [MachineApiController::class, 'index']);
    Route::get('/machines/{machineCode}', [MachineApiController::class, 'show']);
    Route::put('/machines/{machineCode}/status', [MachineApiController::class, 'updateStatus']);
    Route::post('/machines/{machineCode}/heartbeat', [MachineApiController::class, 'heartbeat']);

    // Session routes
    Route::post('/sessions/start', [SessionApiController::class, 'start']);
    Route::put('/sessions/{sessionId}/end', [SessionApiController::class, 'end']);
    Route::put('/sessions/{sessionId}/extend', [SessionApiController::class, 'extend']);
    Route::get('/sessions/{sessionId}', [SessionApiController::class, 'show']);
    Route::get('/machines/{machineCode}/session', [SessionApiController::class, 'getByMachine']);

    // Coin routes
    Route::post('/coins/insert', [CoinApiController::class, 'insert']);
    Route::get('/machines/{machineCode}/coins/today', [CoinApiController::class, 'getTodayCoins']);
});
