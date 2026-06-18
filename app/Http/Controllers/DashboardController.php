<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\PcUnit;
use App\Models\Session;
use App\Models\CoinInsert;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real data.
     */
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get today's earnings total from coin inserts
        $todayEarnings = CoinInsert::whereDate('inserted_time', $today)
            ->sum('coin_value');

        // Get yesterday's earnings for comparison
        $yesterdayEarnings = CoinInsert::whereDate('inserted_time', $yesterday)
            ->sum('coin_value');

        // Calculate percentage change
        $earningsChange = 0;
        if ($yesterdayEarnings > 0) {
            $earningsChange = (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        }

        // Get today's transaction count
        $todayTransactions = Transaction::whereDate('transaction_date', $today)
            ->count();

        // Get yesterday's transaction count for comparison
        $yesterdayTransactions = Transaction::whereDate('transaction_date', $yesterday)
            ->count();

        $transactionChange = 0;
        if ($yesterdayTransactions > 0) {
            $transactionChange = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        }

        // Calculate average transaction amount
        $avgTransaction = $todayTransactions > 0 ? $todayEarnings / $todayTransactions : 0;

        // Get recent transactions (latest 10)
        $recentTransactions = Transaction::where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Simplified earnings breakdown - all from coin inserts
        $totalTodayEarnings = $todayEarnings;
        $gamingPercent = 100;
        $browsingPercent = 0;
        $printingPercent = 0;

        // Get active terminals from coin inserts
        $activeTerminals = CoinInsert::whereDate('inserted_time', $today)
            ->distinct()
            ->pluck('pc_unit_id')
            ->toArray();

        // Generate machine statuses based on recent activity
        $machines = $this->generateMachineStatuses($activeTerminals);

        // Get activity feed (recent transactions with different types)
        $activities = $this->generateActivityFeed($recentTransactions);

        // Get weekly earnings for chart
        $weeklyData = $this->getWeeklyEarningsData();
        $weeklyEarnings = $weeklyData['earnings'];
        $weeklyLabels = $weeklyData['labels'];

        return view('dashboard', compact(
            'todayEarnings',
            'earningsChange',
            'todayTransactions',
            'transactionChange',
            'avgTransaction',
            'recentTransactions',
            'gamingPercent',
            'browsingPercent',
            'printingPercent',
            'totalTodayEarnings',
            'machines',
            'activities',
            'weeklyEarnings',
            'weeklyLabels'
        ));
    }

    /**
     * API endpoint for real-time dashboard data (JSON)
     */
    public function realtimeData(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get today's earnings total from coin inserts
        $todayEarnings = CoinInsert::whereDate('inserted_time', $today)
            ->sum('coin_value');

        // Get yesterday's earnings for comparison
        $yesterdayEarnings = CoinInsert::whereDate('inserted_time', $yesterday)
            ->sum('coin_value');

        // Calculate percentage change
        $earningsChange = 0;
        if ($yesterdayEarnings > 0) {
            $earningsChange = (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        }

        // Get today's transaction count
        $todayTransactions = Transaction::whereDate('transaction_date', $today)
            ->count();

        // Get yesterday's transaction count for comparison
        $yesterdayTransactions = Transaction::whereDate('transaction_date', $yesterday)
            ->count();

        $transactionChange = 0;
        if ($yesterdayTransactions > 0) {
            $transactionChange = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        }

        // Calculate average transaction amount
        $avgTransaction = $todayTransactions > 0 ? $todayEarnings / $todayTransactions : 0;

        // Get recent transactions (latest 10)
        $recentTransactions = Transaction::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get active terminals from coin inserts
        $activeTerminals = CoinInsert::whereDate('inserted_time', $today)
            ->distinct()
            ->pluck('pc_unit_id')
            ->toArray();

        // Generate machine statuses
        $machines = $this->generateMachineStatuses($activeTerminals);

        return response()->json([
            'success' => true,
            'data' => [
                'todayEarnings' => (float) $todayEarnings,
                'earningsChange' => (float) $earningsChange,
                'todayTransactions' => (int) $todayTransactions,
                'transactionChange' => (float) $transactionChange,
                'avgTransaction' => (float) $avgTransaction,
                'machines' => $machines,
                'recentTransactions' => $recentTransactions->map(function($t) {
                    return [
                        'terminal' => $t->pc_unit_id ?? 'N/A',
                        'amount' => (float) $t->total_coins,
                        'minutes' => (int) $t->total_minutes,
                        'date_and_time' => $t->created_at->toIso8601String(),
                        'status' => $t->status
                    ];
                }),
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Get weekly earnings data for charts
     */
    private function getWeeklyEarningsData(): array
    {
        $labels = [];
        $earnings = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayName = $date->format('D');
            $labels[] = $dayName;
            
            $dayEarnings = CoinInsert::whereDate('inserted_time', $date)
                ->sum('coin_value');
            
            $earnings[] = (float) $dayEarnings;
        }
        
        return ['labels' => $labels, 'earnings' => $earnings];
    }

    /**
     * Generate machine statuses based on real PC Units and Sessions
     */
    private function generateMachineStatuses(array $activeTerminals): array
    {
        // Get all machines from database (these are the actual PCs with coin slots)
        $machines = \App\Models\Machine::orderBy('machine_code', 'asc')->get();
        
        // If no machines in database, return empty array
        if ($machines->isEmpty()) {
            return [];
        }
        
        // Get active sessions to determine which PCs are in use
        $activeSessions = Session::where('status', 'Active')
            ->get()
            ->keyBy(function($session) {
                return $session->pc_unit_number;
            });
        
        $result = [];
        
        foreach ($machines as $machine) {
            $machineCode = $machine->machine_code; // e.g., "PC-01"
            
            // Extract PC number for session lookup
            $pcNumber = (int) str_replace('PC-', '', $machineCode);
            
            // Check if machine has an active session
            $activeSession = $activeSessions->get($pcNumber);
            
            // Check heartbeat age first
            $isOnline = false;
            if ($machine->last_heartbeat) {
                $heartbeatAge = $machine->last_heartbeat->diffInSeconds(now());
                $isOnline = ($heartbeatAge <= 60); // Online if heartbeat within 60 seconds
            }
            
            // Determine status and time display
            if ($activeSession && $isOnline) {
                // Machine is BUSY - has active session AND is online
                $status = 'busy';
                $remainingSeconds = $activeSession->remaining_time * 60; // Convert minutes to seconds
                
                // Return raw seconds for JavaScript countdown
                $time = $remainingSeconds;
                $displayType = 'countdown';
                
            } elseif ($isOnline && !$activeSession) {
                // Machine is ONLINE - heartbeat received, no active session
                $status = 'online';
                $time = 'Ready';
                $displayType = 'text';
            } else {
                // Machine is OFFLINE - no heartbeat or timeout
                $status = 'offline';
                $time = 'Disconnected';
                $displayType = 'text';
            }
            
            $result[] = [
                'id' => $machineCode,
                'status' => $status,
                'time' => $time,
                'displayType' => $displayType,
                'last_heartbeat' => $machine->last_heartbeat ? $machine->last_heartbeat->diffForHumans() : 'Never'
            ];
        }
        
        return $result;
    }

    /**
     * Generate activity feed from recent transactions
     */
    private function generateActivityFeed($transactions): array
    {
        $activities = [];
        
        foreach ($transactions->take(5) as $txn) {
            $minutesAgo = Carbon::parse($txn->transaction_date)->diffInMinutes(Carbon::now());
            $timeAgo = $minutesAgo < 1 ? 'Just now' : ($minutesAgo < 60 ? $minutesAgo . ' min ago' : Carbon::parse($txn->transaction_date)->format('H:i'));
            
            $activities[] = [
                'dot' => '#00c8ff',
                'title' => 'Transaction Completed',
                'sub' => $txn->pc_unit_id . ' · ₱' . number_format($txn->total_coins, 2),
                'time' => $timeAgo
            ];
        }
        
        return $activities;
    }

    /**
     * API endpoint for Dashboard Dataset - returns system summary data
     */
    public function getDataset(Request $request)
    {
        $today = Carbon::today();
        
        // Get total PC units count
        $totalPcUnits = PcUnit::count();
        
        // Get active PC units (is_active = true)
        $activePcUnits = PcUnit::where('is_active', true)->count();
        
        // Get available PC units (status = 'available')
        $availablePcUnits = PcUnit::where('status', 'available')->count();
        
        // Get total sessions today (from Session model)
        $totalSessionsToday = Session::whereDate('start_time', $today)->count();
        
        // Get total income today from coin inserts
        $totalIncomeToday = CoinInsert::whereDate('inserted_time', $today)
            ->sum('coin_value');
        
        // Get coins inserted today (from CoinInsert model)
        $coinsInsertedToday = CoinInsert::whereDate('inserted_time', $today)
            ->sum('coin_value');
        
        // Calculate system status based on available metrics
        $systemStatus = $this->calculateSystemStatus($totalPcUnits, $activePcUnits, $totalSessionsToday, $totalIncomeToday);
        
        // Get yesterday's data for comparison
        $yesterday = Carbon::yesterday();
        $yesterdayIncome = CoinInsert::whereDate('inserted_time', $yesterday)
            ->sum('coin_value');
        $yesterdaySessions = Session::whereDate('start_time', $yesterday)->count();
        
        // Calculate changes
        $incomeChange = 0;
        if ($yesterdayIncome > 0) {
            $incomeChange = (($totalIncomeToday - $yesterdayIncome) / $yesterdayIncome) * 100;
        }
        
        $sessionsChange = 0;
        if ($yesterdaySessions > 0) {
            $sessionsChange = (($totalSessionsToday - $yesterdaySessions) / $yesterdaySessions) * 100;
        }
        
        // Get additional stats for dashboard
        $pcUtilization = $totalPcUnits > 0 ? ($activePcUnits / $totalPcUnits) * 100 : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_pc_units' => (int) $totalPcUnits,
                'active_pc_units' => (int) $activePcUnits,
                'available_pc_units' => (int) $availablePcUnits,
                'total_sessions_today' => (int) $totalSessionsToday,
                'total_income_today' => (float) $totalIncomeToday,
                'coins_inserted_today' => (float) $coinsInsertedToday,
                'system_status' => $systemStatus,
                // Additional metrics
                'pc_utilization' => (float) $pcUtilization,
                'income_change' => (float) $incomeChange,
                'sessions_change' => (float) $sessionsChange,
                'yesterday_income' => (float) $yesterdayIncome,
                'yesterday_sessions' => (int) $yesterdaySessions,
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Calculate system status based on various metrics
     */
    private function calculateSystemStatus(int $totalPcUnits, int $activePcUnits, int $sessionsToday, float $incomeToday): string
    {
        // If no PC units configured, system is offline
        if ($totalPcUnits === 0) {
            return 'offline';
        }
        
        // Calculate active percentage
        $activePercentage = $totalPcUnits > 0 ? ($activePcUnits / $totalPcUnits) * 100 : 0;
        
        // If more than 50% of PCs are inactive, system has issues
        if ($activePercentage < 50) {
            return 'degraded';
        }
        
        // If we have active PCs but no sessions and no income today, system might be idle
        if ($activePercentage >= 50 && $activePercentage < 80) {
            return 'operational';
        }
        
        // If 80%+ PCs are active and we have sessions/income, system is optimal
        if ($activePercentage >= 80 && ($sessionsToday > 0 || $incomeToday > 0)) {
            return 'optimal';
        }
        
        // Default to operational
        return 'operational';
    }
}
