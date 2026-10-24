<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Session;
use App\Models\CoinTransaction;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CoinTestController extends Controller
{
    private function pendingKey($machineCode): string
    {
        return "pending_coins_{$machineCode}";
    }

    public function index()
    {
        $machines = Machine::all();
        return view('coin-test.index', compact('machines'));
    }

    public function session($machineCode)
    {
        $pcNumber   = (int) str_replace('PC-', '', $machineCode);
        $machine    = Machine::where('machine_code', $machineCode)->first();
        $session    = Session::where('pc_unit_number', $pcNumber)->where('status', 'Active')->first();
        $todayCoins = $machine
            ? CoinTransaction::where('machine_id', $machine->id)
                ->whereDate('created_at', today())
                ->orderByDesc('created_at')
                ->get(['coins_inserted', 'minutes_purchased', 'amount', 'created_at'])
            : collect();
        $pending     = Cache::get($this->pendingKey($machineCode), ['coins' => 0, 'minutes' => 0, 'amount' => 0]);
        $slotEnabled = $machine ? (bool) $machine->slot_enabled : false;
        $rates = [
            '1'  => (int) Setting::get('coin_1_rate',  5),
            '5'  => (int) Setting::get('coin_5_rate',  25),
            '10' => (int) Setting::get('coin_10_rate', 50),
            '20' => (int) Setting::get('coin_20_rate', 100),
        ];

        if (!$session) {
            return response()->json(['success' => false, 'today_coins' => $todayCoins, 'rates' => $rates, 'pending' => $pending, 'slot_enabled' => $slotEnabled]);
        }

        $elapsed   = (int) $session->start_time->diffInMinutes(now());
        $remaining = max(0, $session->remaining_time - $elapsed);

        return response()->json([
            'success'        => true,
            'session_id'     => $session->session_id,
            'remaining_time' => $remaining,
            'start_time'     => $session->start_time?->format('h:i A'),
            'today_coins'    => $todayCoins,
            'rates'          => $rates,
            'pending'        => $pending,
            'slot_enabled'   => $slotEnabled,
        ]);
    }

    public function done($machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        if (!$machine) return response()->json(['success' => false, 'message' => 'Machine not found']);

        $key     = $this->pendingKey($machineCode);
        $pending = Cache::get($key, ['coins' => 0, 'minutes' => 0, 'amount' => 0]);

        if ($pending['coins'] <= 0) {
            return response()->json(['success' => false, 'message' => 'No pending coins']);
        }

        $machine->update(['slot_enabled' => 0]);

        CoinTransaction::create([
            'machine_id'        => $machine->id,
            'coins_inserted'    => $pending['coins'],
            'minutes_purchased' => $pending['minutes'],
            'amount'            => $pending['amount'],
        ]);

        $pcNumber = (int) str_replace('PC-', '', $machineCode);
        $session  = Session::where('pc_unit_number', $pcNumber)->where('status', 'Active')->first();

        if ($session) {
            $elapsed  = (int) $session->start_time->diffInMinutes(now());
            $realLeft = max(0, $session->remaining_time - $elapsed);
            $session->update([
                'remaining_time' => $realLeft + $pending['minutes'],
                'start_time'     => now(),
            ]);
        } else {
            $session = Session::create([
                'session_id'        => 'SES-' . strtoupper(Str::random(10)),
                'pc_unit_number'    => $pcNumber,
                'user_session_name' => 'Guest',
                'start_time'        => now(),
                'remaining_time'    => $pending['minutes'],
                'status'            => 'Active',
            ]);
        }

        Cache::forget($key);
        // Keep slot closed after done - must manually click Start to open again
        // $machine->update(['slot_enabled' => 1]);

        return response()->json([
            'success'        => true,
            'minutes'        => $pending['minutes'],
            'coins'          => $pending['coins'],
            'amount'         => $pending['amount'],
            'remaining_time' => $session->remaining_time,
        ]);
    }

    public function openSlot($machineCode)
    {
        \Log::info("openSlot called for: " . $machineCode);
        
        // Close all other machines and clear their pending coins
        $allMachines = Machine::where('machine_code', '!=', $machineCode)->get();
        \Log::info("Closing " . $allMachines->count() . " other machines");
        foreach ($allMachines as $machine) {
            \Log::info("Closing: " . $machine->machine_code);
            $machine->update(['slot_enabled' => 0]);
            Cache::forget($this->pendingKey($machine->machine_code));
        }
        
        // Open this machine's slot
        \Log::info("Opening: " . $machineCode);
        Machine::where('machine_code', $machineCode)->update(['slot_enabled' => 1]);
        
        \Log::info("openSlot completed for: " . $machineCode);
        return response()->json(['success' => true]);
    }

    public function closeSlot($machineCode)
    {
        // Don't clear pending coins when closing - coins already accumulated
        // Cache::forget($this->pendingKey($machineCode));
        Machine::where('machine_code', $machineCode)->update(['slot_enabled' => 0]);
        return response()->json(['success' => true]);
    }

    public function cancel($machineCode)
    {
        Cache::forget($this->pendingKey($machineCode));
        Machine::where('machine_code', $machineCode)->update(['slot_enabled' => 1]);
        return response()->json(['success' => true]);
    }

    public function reset($machineCode)
    {
        $pcNumber = (int) str_replace('PC-', '', $machineCode);
        $session  = Session::where('pc_unit_number', $pcNumber)->where('status', 'Active')->first();
        if (!$session) return response()->json(['success' => false, 'message' => 'No active session']);
        $session->update(['status' => 'Ended', 'end_time' => now(), 'remaining_time' => 0]);
        Machine::where('machine_code', $machineCode)->update(['slot_enabled' => 0]);
        return response()->json(['success' => true]);
    }
}
