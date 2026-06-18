<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Machine;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SessionApiController extends Controller
{
    /**
     * Start a new session
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'machine_code' => 'required|string',
            'user_name' => 'nullable|string|max:100',
            'minutes' => 'required|integer|min:1'
        ]);
        
        $machine = Machine::where('machine_code', $validated['machine_code'])->first();
        
        if (!$machine) {
            return response()->json([
                'success' => false,
                'message' => 'Machine not found'
            ], 404);
        }
        
        // Extract PC number from machine code (e.g., PC-01 -> 1)
        $pcNumber = (int) str_replace('PC-', '', $validated['machine_code']);
        
        $session = Session::create([
            'session_id' => 'SES-' . strtoupper(Str::random(10)),
            'pc_unit_number' => $pcNumber,
            'user_session_name' => $validated['user_name'] ?? 'Guest',
            'start_time' => now(),
            'remaining_time' => $validated['minutes'],
            'status' => 'Active'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Session started successfully',
            'data' => $session
        ], 201);
    }
    
    /**
     * End a session
     */
    public function end(Request $request, $sessionId)
    {
        $session = Session::where('session_id', $sessionId)->first();
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found'
            ], 404);
        }
        
        $session->update([
            'end_time' => now(),
            'status' => 'Ended'
        ]);
        
        // Complete related transaction
        $machineCode = 'PC-' . str_pad($session->pc_unit_number, 2, '0', STR_PAD_LEFT);
        $transaction = Transaction::where('pc_unit_id', $machineCode)
            ->where('status', 'active')
            ->orderBy('start_time', 'desc')
            ->first();
        
        if ($transaction) {
            $transaction->update([
                'end_time' => now(),
                'status' => 'completed'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Session ended successfully',
            'data' => $session
        ]);
    }
    
    /**
     * Extend session time
     */
    public function extend(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'additional_minutes' => 'required|integer|min:1'
        ]);
        
        $session = Session::where('session_id', $sessionId)->first();
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found'
            ], 404);
        }
        
        if ($session->status !== 'Active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot extend inactive session'
            ], 400);
        }
        
        $session->update([
            'remaining_time' => $session->remaining_time + $validated['additional_minutes']
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Session extended successfully',
            'data' => $session
        ]);
    }
    
    /**
     * Get session details
     */
    public function show($sessionId)
    {
        $session = Session::where('session_id', $sessionId)->first();
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $session
        ]);
    }
    
    /**
     * Get active session for a machine
     */
    public function getByMachine($machineCode)
    {
        // Extract PC number from machine code
        $pcNumber = (int) str_replace('PC-', '', $machineCode);
        
        $session = Session::where('pc_unit_number', $pcNumber)
            ->where('status', 'Active')
            ->first();
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No active session found for this machine'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $session
        ]);
    }
}
