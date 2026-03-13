<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Display a listing of the sessions (active session monitoring).
     */
    public function index(Request $request)
    {
        $query = Session::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('session_id', 'like', "%{$search}%")
                  ->orWhere('pc_unit_number', 'like', "%{$search}%")
                  ->orWhere('user_session_name', 'like', "%{$search}%");
            });
        }

        // Get active sessions for monitoring
        $activeSessions = Session::active()
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($session) {
                // Update remaining time based on start_time and original remaining time
                $elapsedMinutes = $session->start_time->diffInMinutes(Carbon::now());
                $session->current_remaining = max(0, $session->remaining_time - $elapsedMinutes);
                return $session;
            });

        // Get all sessions (for history)
        $sessions = $query->orderBy('created_at', 'desc')->get();

        // Statistics
        $stats = [
            'total' => Session::count(),
            'active' => Session::active()->count(),
            'ended' => Session::ended()->count(),
        ];

        return view('sessions.index', compact('sessions', 'activeSessions', 'stats'));
    }

    /**
     * Store a newly created session (when user starts using a PC).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_unit_number' => 'required|string|max:50',
            'user_session_name' => 'required|string|max:100',
            'remaining_time' => 'required|integer|min:1',
        ]);

        $validated['session_id'] = Session::generateSessionId();
        $validated['start_time'] = Carbon::now();
        $validated['status'] = 'Active';

        $session = Session::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Session started successfully!',
            'session' => $session,
        ]);
    }

    /**
     * End an active session (Admin action).
     */
    public function endSession(Session $session)
    {
        if ($session->status !== 'Active') {
            return redirect()->route('sessions.index')
                ->with('error', 'This session is already ended!');
        }

        $session->endSession();

        return redirect()->route('sessions.index')
            ->with('success', 'Session ended successfully!');
    }

    /**
     * End session by ID (alternative route).
     */
    public function endSessionById($sessionId)
    {
        $session = Session::where('session_id', $sessionId)->firstOrFail();
        
        if ($session->status !== 'Active') {
            return redirect()->route('sessions.index')
                ->with('error', 'This session is already ended!');
        }

        $session->endSession();

        return redirect()->route('sessions.index')
            ->with('success', 'Session ended successfully!');
    }

    /**
     * Get active sessions for real-time monitoring (API).
     */
    public function getActiveSessions()
    {
        $activeSessions = Session::active()
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($session) {
                $elapsedMinutes = $session->start_time->diffInMinutes(Carbon::now());
                $session->current_remaining = max(0, $session->remaining_time - $elapsedMinutes);
                return $session;
            });

        return response()->json([
            'success' => true,
            'sessions' => $activeSessions,
            'count' => $activeSessions->count(),
        ]);
    }

    /**
     * Extend session time.
     */
    public function extendSession(Request $request, Session $session)
    {
        if ($session->status !== 'Active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot extend an ended session!',
            ], 400);
        }

        $validated = $request->validate([
            'additional_time' => 'required|integer|min:1|max:480', // max 8 hours
        ]);

        $session->update([
            'remaining_time' => $session->remaining_time + $validated['additional_time'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session extended successfully!',
            'remaining_time' => $session->remaining_time,
        ]);
    }
}

