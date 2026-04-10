<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\Machine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoinApiController extends Controller
{
    /**
     * Record coin insert
     */
    public function insert(Request $request)
    {
        $validated = $request->validate([
            'machine_code' => 'required|string',
            'coins_inserted' => 'required|integer|min:1',
            'minutes_purchased' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0'
        ]);
        
        $machine = Machine::where('machine_code', $validated['machine_code'])->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        $coinTransaction = CoinTransaction::create([
            'machine_id' => $machine->id,
            'coins_inserted' => $validated['coins_inserted'],
            'minutes_purchased' => $validated['minutes_purchased'],
            'amount' => $validated['amount']
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Coin transaction recorded successfully',
            'data' => $coinTransaction
        ], 201);
    }
    
    /**
     * Get today's coin transactions for a machine
     */
    public function getTodayCoins($machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        $todayTransactions = CoinTransaction::where('machine_id', $machine->id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        
        $totalCoins = $todayTransactions->sum('coins_inserted');
        $totalAmount = $todayTransactions->sum('amount');
        $totalMinutes = $todayTransactions->sum('minutes_purchased');
        
        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $todayTransactions,
                'summary' => [
                    'total_coins' => $totalCoins,
                    'total_amount' => $totalAmount,
                    'total_minutes' => $totalMinutes,
                    'transaction_count' => $todayTransactions->count()
                ]
            ]
        ]);
    }
}
