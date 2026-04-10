<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachineApiController extends Controller
{
    /**
     * Get all machines
     */
    public function index()
    {
        $machines = Machine::all();
        
        return response()->json([
            'success' => true,
            'data' => $machines,
            'count' => $machines->count()
        ]);
    }
    
    /**
     * Get specific machine by code
     */
    public function show($machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $machine
        ]);
    }
    
    /**
     * Update machine status
     */
    public function updateStatus(Request $request, $machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:online,offline,maintenance'
        ]);
        
        $machine->update(['status' => $validated['status']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Machine status updated successfully',
            'data' => $machine
        ]);
    }
    
    /**
     * Machine heartbeat (keep-alive)
     */
    public function heartbeat(Request $request, $machineCode)
    {
        $machine = Machine::where('machine_code', $machineCode)->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        // Update last_seen timestamp
        $machine->update([
            'status' => 'online',
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
