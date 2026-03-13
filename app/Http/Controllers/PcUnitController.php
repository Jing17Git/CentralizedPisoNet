<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PcUnit;
use Carbon\Carbon;

class PcUnitController extends Controller
{
    /**
     * Display a listing of the PC units.
     */
    public function index(Request $request)
    {
        $query = PcUnit::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pc_number', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $pcUnits = $query->orderBy('pc_number', 'asc')->get();

        // Statistics
        $stats = [
            'total' => PcUnit::count(),
            'active' => PcUnit::where('is_active', true)->count(),
            'available' => PcUnit::where('status', 'available')->count(),
            'in_use' => PcUnit::where('status', 'in_use')->count(),
            'offline' => PcUnit::where('status', 'offline')->count(),
        ];

        return view('pcunits.index', compact('pcUnits', 'stats'));
    }

    /**
     * Store a newly created PC unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_number' => 'required|string|max:20|unique:pc_units,pc_number',
            'branch_id' => 'nullable|integer|min:1',
            'ip_address' => 'nullable|ip',
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
            'ip_address' => 'nullable|ip',
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

