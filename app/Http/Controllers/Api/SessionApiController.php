<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Machine;
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
        
        $session = Session::create([
            'session_id' => Session::generateSessionId(),
            'pc_unit_number' => $validated['machine_code'],
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
        $session = Session::where(function ($q) use ($machineCode) {
                $q->where('pc_unit_number', $machineCode);
                if (preg_match('/\d+/', $machineCode, $matches)) {
                    $q->orWhere('pc_unit_number', (string) (int) $matches[0]);
                }
            })
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
