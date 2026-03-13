<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\Session;
use App\Models\CoinTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'today');
        
        $dateRange = $this->getDateRange($filter);
        
        // Summary Statistics
        $totalEarningsToday = CoinTransaction::whereDate('created_at', Carbon::today())
            ->sum('amount');
        
        $totalCoinsToday = CoinTransaction::whereDate('created_at', Carbon::today())
            ->sum('coins_inserted');
        
        $activeMachines = Machine::where('status', 'online')->count();
        
        $totalSessionsToday = DB::table('pc_sessions')->whereDate('start_time', Carbon::today())->count();
        
        // Daily Earnings Chart Data (Last 7 days)
        $dailyEarnings = CoinTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Machine Usage Data
        $machineUsage = Machine::select('machines.*')
            ->withCount(['sessions' => function($query) {
                $query->select(DB::raw('count(*)'));
            }])
            ->get();
        
        return view('admin.reports.index', compact(
            'totalEarningsToday',
            'totalCoinsToday',
            'activeMachines',
            'totalSessionsToday',
            'dailyEarnings',
            'machineUsage',
            'filter'
        ));
    }
    
    public function earnings(Request $request)
    {
        $query = CoinTransaction::with('machine')
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->has('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }
        
        if ($request->has('filter')) {
            $dateRange = $this->getDateRange($request->filter);
            $query->whereBetween('created_at', $dateRange);
        }
        
        $earnings = $query->paginate(20);
        $machines = Machine::all();
        
        return view('admin.reports.earnings', compact('earnings', 'machines'));
    }
    
    public function machines(Request $request)
    {
        $machines = Machine::select('machines.*')
            ->withCount(['sessions' => function($query) {
                $query->select(DB::raw('count(*)'));
            }])
            ->withSum('coinTransactions', 'coins_inserted')
            ->get();
        
        // Calculate minutes used manually
        foreach ($machines as $machine) {
            $machine->total_minutes = DB::table('pc_sessions')
                ->where('pc_unit_number', $machine->machine_code)
                ->sum(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, COALESCE(end_time, NOW()))'));
        }
        
        return view('admin.reports.machines', compact('machines'));
    }
    
    public function sessions(Request $request)
    {
        $query = DB::table('pc_sessions')
            ->orderBy('start_time', 'desc');
        
        if ($request->has('machine_id')) {
            $machine = Machine::find($request->machine_id);
            if ($machine) {
                $query->where('pc_unit_number', $machine->machine_code);
            }
        }
        
        if ($request->has('date')) {
            $query->whereDate('start_time', $request->date);
        }
        
        $sessions = $query->paginate(20);
        
        // Calculate minutes used for each session
        foreach ($sessions as $session) {
            if ($session->end_time) {
                $start = Carbon::parse($session->start_time);
                $end = Carbon::parse($session->end_time);
                $session->minutes_used = $start->diffInMinutes($end);
            } else {
                $start = Carbon::parse($session->start_time);
                $session->minutes_used = $start->diffInMinutes(Carbon::now());
            }
        }
        
        $machines = Machine::all();
        
        return view('admin.reports.sessions', compact('sessions', 'machines'));
    }
    
    private function getDateRange($filter)
    {
        return match($filter) {
            'today' => [Carbon::today(), Carbon::now()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()],
            default => [Carbon::today(), Carbon::now()],
        };
    }
}
