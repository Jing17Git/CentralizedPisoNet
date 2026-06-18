<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PcUnit;
use App\Models\Session;
use App\Models\Machine;
use App\Models\CoinTransaction;
use Carbon\Carbon;

class PcUnitController extends Controller
{
    public function index(Request $request)
    {
        // Get all machines (actual PCs with coin slots)
        $machines = Machine::orderBy('machine_code')->get();

        // Active sessions keyed by pc_unit_number
        $sessions = Session::where('status', 'Active')
            ->get()
            ->keyBy('pc_unit_number');

        // Recent session history per PC (last 5)
        $histories = Session::orderByDesc('start_time')
            ->get()
            ->groupBy('pc_unit_number')
            ->map(fn($s) => $s->take(5));

        // Today's coin transactions per machine
        $allTodayCoins = CoinTransaction::whereDate('created_at', today())->get();
        $todayCoins = [];
        foreach ($machines as $machine) {
            $todayCoins[$machine->id] = $allTodayCoins->where('machine_id', $machine->id);
        }

        // Calculate stats
        $onlineCount = 0;
        $busyCount = 0;
        $offlineCount = 0;

        foreach ($machines as $machine) {
            $pcNumber = (int) str_replace('PC-', '', $machine->machine_code);
            $hasSession = isset($sessions[$pcNumber]);
            
            if ($hasSession) {
                $busyCount++;
            } elseif ($machine->status === 'online') {
                $onlineCount++;
            } else {
                $offlineCount++;
            }
        }

        $stats = [
            'total' => $machines->count(),
            'online' => $onlineCount,
            'busy' => $busyCount,
            'offline' => $offlineCount,
        ];

        return view('pcunits.index', compact('machines', 'stats', 'sessions', 'histories', 'todayCoins'));
    }

    /**
     * API endpoint for real-time PC status updates
     */
    public function realtimeStatus(Request $request)
    {
        $machines = Machine::orderBy('machine_code')->get();
        $sessions = Session::where('status', 'Active')->get()->keyBy('pc_unit_number');
        $allTodayCoins = CoinTransaction::whereDate('created_at', today())->get();

        $data = [];
        foreach ($machines as $machine) {
            $pcNumber = (int) str_replace('PC-', '', $machine->machine_code);
            $session = $sessions->get($pcNumber);
            $todayCoins = $allTodayCoins->where('machine_id', $machine->id);

            $status = 'offline';
            $remainingTime = null;

            if ($session) {
                $status = 'busy';
                $remainingTime = $session->remaining_time;
            } elseif ($machine->status === 'online') {
                $status = 'online';
            }

            $data[] = [
                'machine_code' => $machine->machine_code,
                'status' => $status,
                'remaining_time' => $remainingTime,
                'last_heartbeat' => $machine->last_heartbeat?->diffForHumans(),
                'coins_today' => $todayCoins->sum('coins_inserted'),
                'minutes_today' => $todayCoins->sum('minutes_purchased'),
                'earned_today' => $todayCoins->sum('amount'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Store a newly created PC unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_number' => 'required|string|max:20|unique:pc_units,pc_number',
            'branch_id' => 'nullable|integer|min:1',
            'ip_address' => 'required|ip|unique:pc_units,ip_address',
            'status' => 'nullable|in:available,in_use,offline',
        ]);

        $validated['branch_id'] = $validated['branch_id'] ?? 1;
        $validated['status'] = $validated['status'] ?? 'available';
        $validated['is_active'] = true;

        PcUnit::create($validated);

        return redirect()->route('pcunits.index')
            ->with('success', 'PC Unit added successfully!');
    }

    /**
     * Update the specified PC unit.
     */
    public function update(Request $request, PcUnit $pcUnit)
    {
        $validated = $request->validate([
            'pc_number' => 'required|string|max:20|unique:pc_units,pc_number,' . $pcUnit->id,
            'branch_id' => 'nullable|integer|min:1',
            'ip_address' => 'required|ip|unique:pc_units,ip_address,' . $pcUnit->id,
            'status' => 'nullable|in:available,in_use,offline',
        ]);

        $pcUnit->update($validated);

        return redirect()->route('pcunits.index')
            ->with('success', 'PC Unit updated successfully!');
    }

    /**
     * Remove the specified PC unit.
     */
    public function destroy(PcUnit $pcUnit)
    {
        $pcUnit->delete();

        return redirect()->route('pcunits.index')
            ->with('success', 'PC Unit deleted successfully!');
    }

    /**
     * Toggle active status of PC unit.
     */
    public function toggleActive(PcUnit $pcUnit)
    {
        $pcUnit->toggleActive();

        $status = $pcUnit->is_active ? 'activated' : 'deactivated';

        return redirect()->route('pcunits.index')
            ->with('success', "PC Unit {$status} successfully!");
    }

    /**
     * Update status of PC unit.
     */
    public function updateStatus(Request $request, PcUnit $pcUnit)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,in_use,offline',
        ]);

        $pcUnit->update([
            'status' => $validated['status'],
            'last_activity' => $validated['status'] === 'in_use' ? Carbon::now() : $pcUnit->last_activity,
        ]);

        return redirect()->route('pcunits.index')
            ->with('success', 'PC Unit status updated successfully!');
    }
}