<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\Machine;
use App\Models\Session;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CoinApiController extends Controller
{
    // ── Pending coin key per machine ─────────────────────────────────────────
    private function pendingKey($machineCode): string
    {
        return "pending_coins_{$machineCode}";
    }

    // ── Coin slot enabled key per machine ────────────────────────────────────
    private function slotKey($machineCode): string
    {
        return "coin_slot_enabled_{$machineCode}";
    }

    /**
     * Called by coin_listener.py on each pulse group detected.
     * Accumulates coins without processing — Pin 5 stays HIGH.
     */
    public function insert(Request $request)
    {
        $validated = $request->validate([
            'machine_code'     => 'required|string',
            'coins_inserted'   => 'required|integer|min:1',
            'minutes_purchased'=> 'required|integer|min:1',
            'amount'           => 'required|numeric|min:0',
        ]);

        $machine = Machine::where('machine_code', $validated['machine_code'])->first();
        if (!$machine) return response()->json(['success'=>false,'message'=>'Machine not found'], 404);

        // Accumulate in cache (expires in 10 minutes)
        $key     = $this->pendingKey($validated['machine_code']);
        $pending = Cache::get($key, ['coins'=>0,'minutes'=>0,'amount'=>0]);
        $pending['coins']   += $validated['coins_inserted'];
        $pending['minutes'] += $validated['minutes_purchased'];
        $pending['amount']  += $validated['amount'];
        Cache::put($key, $pending, 600);

        return response()->json([
            'success' => true,
            'message' => 'Coin accumulated',
            'pending' => $pending,
        ]);
    }

    /**
     * Get current pending (not yet processed) coins for a machine.
     */
    public function getPending($machineCode)
    {
        $pending     = Cache::get($this->pendingKey($machineCode), ['coins'=>0,'minutes'=>0,'amount'=>0]);
        $machine     = Machine::where('machine_code', $machineCode)->first();
        $slotEnabled = $machine ? (bool) $machine->slot_enabled : false;

        return response()->json([
            'success'      => true,
            'pending'      => $pending,
            'slot_enabled' => $slotEnabled,
        ]);
    }

    /**
     * User clicked Done — disable coin slot, process all pending coins, start/extend session.
     */
    public function done($machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        if (!$machine) return response()->json(['success'=>false,'message'=>'Machine not found'], 404);

        $key     = $this->pendingKey($machineCode);
        $pending = Cache::get($key, ['coins'=>0,'minutes'=>0,'amount'=>0]);

        if ($pending['coins'] <= 0) {
            return response()->json(['success'=>false,'message'=>'No pending coins']);
        }

        // Signal coin listener to disable Pin 5
        $machine->update(['slot_enabled' => 0]);

        // Record coin transaction
        CoinTransaction::create([
            'machine_id'        => $machine->id,
            'coins_inserted'    => $pending['coins'],
            'minutes_purchased' => $pending['minutes'],
            'amount'            => $pending['amount'],
        ]);

        // Start or extend session
        $pcNumber  = (int) str_replace('PC-', '', $machineCode);
        $session   = Session::where('pc_unit_number', $pcNumber)->where('status', 'Active')->first();

        if ($session) {
            $session->update(['remaining_time' => $session->remaining_time + $pending['minutes']]);
            $message = "Session extended by {$pending['minutes']} minutes";
            
            // Update active transaction record
            $transaction = Transaction::where('pc_unit_id', $machineCode)
                ->where('status', 'active')
                ->orderBy('start_time', 'desc')
                ->first();
            
            if ($transaction) {
                $transaction->update([
                    'total_coins' => $transaction->total_coins + $pending['amount'],
                    'total_minutes' => $transaction->total_minutes + $pending['minutes'],
                ]);
            }
        } else {
            $session = Session::create([
                'session_id'        => 'SES-' . strtoupper(Str::random(10)),
                'pc_unit_number'    => $pcNumber,
                'user_session_name' => 'Guest',
                'start_time'        => now(),
                'remaining_time'    => $pending['minutes'],
                'status'            => 'Active',
            ]);
            $message = "Session started with {$pending['minutes']} minutes";
            
            // Create transaction record for Transactions page
            Transaction::create([
                'transaction_id'   => Transaction::generateTransactionId(),
                'pc_unit_id'       => $machineCode,
                'total_coins'      => $pending['amount'],
                'total_minutes'    => $pending['minutes'],
                'start_time'       => now(),
                'transaction_date' => now()->toDateString(),
                'status'           => 'active',
            ]);
        }

        // Clear pending coins
        Cache::forget($key);
        // Keep slot closed after session starts
        // $machine->update(['slot_enabled' => 1]);

        return response()->json([
            'success'  => true,
            'message'  => $message,
            'minutes'  => $pending['minutes'],
            'coins'    => $pending['coins'],
            'amount'   => $pending['amount'],
            'session'  => $session,
        ]);
    }

    /**
     * User clicked Cancel — clear pending coins, re-enable slot.
     */
    public function cancel($machineCode)
    {
        Cache::forget($this->pendingKey($machineCode));
        $machine = Machine::where('machine_code', $machineCode)->first();
        if ($machine) $machine->update(['slot_enabled' => 1]);

        return response()->json(['success'=>true,'message'=>'Pending coins cancelled']);
    }

    /**
     * Get today's coin transactions for a machine.
     */
    public function getTodayCoins($machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        if (!$machine) return response()->json(['success'=>false,'message'=>'Machine not found'], 404);

        $today = CoinTransaction::where('machine_id', $machine->id)
            ->whereDate('created_at', Carbon::today())
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'transactions' => $today,
                'summary'      => [
                    'total_coins'       => $today->sum('coins_inserted'),
                    'total_amount'      => $today->sum('amount'),
                    'total_minutes'     => $today->sum('minutes_purchased'),
                    'transaction_count' => $today->count(),
                ],
            ],
        ]);
    }
}
