<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Earning;
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

        // Get today's earnings total
        $todayEarnings = Earning::whereDate('date_and_time', $today)
            ->where('status', 'completed')
            ->sum('amount');

        // Get yesterday's earnings for comparison
        $yesterdayEarnings = Earning::whereDate('date_and_time', $yesterday)
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate percentage change
        $earningsChange = 0;
        if ($yesterdayEarnings > 0) {
            $earningsChange = (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        }

        // Get today's transaction count
        $todayTransactions = Earning::whereDate('date_and_time', $today)
            ->where('status', 'completed')
            ->count();

        // Get yesterday's transaction count for comparison
        $yesterdayTransactions = Earning::whereDate('date_and_time', $yesterday)
            ->where('status', 'completed')
            ->count();

        $transactionChange = 0;
        if ($yesterdayTransactions > 0) {
            $transactionChange = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        }

        // Calculate average transaction amount
        $avgTransaction = $todayTransactions > 0 ? $todayEarnings / $todayTransactions : 0;

        // Get recent transactions (latest 10)
        $recentTransactions = Earning::where('status', 'completed')
            ->orderBy('date_and_time', 'desc')
            ->limit(10)
            ->get();

        // Get earnings breakdown by type for today
        $gamingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'gaming')
            ->where('status', 'completed')
            ->sum('amount');

        $browsingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'browsing')
            ->where('status', 'completed')
            ->sum('amount');

        $printingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'printing')
            ->where('status', 'completed')
            ->sum('amount');

        $totalTodayEarnings = $gamingEarnings + $browsingEarnings + $printingEarnings;

        // Calculate percentages
        $gamingPercent = $totalTodayEarnings > 0 ? ($gamingEarnings / $totalTodayEarnings) * 100 : 0;
        $browsingPercent = $totalTodayEarnings > 0 ? ($browsingEarnings / $totalTodayEarnings) * 100 : 0;
        $printingPercent = $totalTodayEarnings > 0 ? ($printingEarnings / $totalTodayEarnings) * 100 : 0;

        // Get unique terminals with activity today (for machine status simulation)
        $activeTerminals = Earning::whereDate('date_and_time', $today)
            ->distinct()
            ->pluck('terminal')
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

        // Get today's earnings total
        $todayEarnings = Earning::whereDate('date_and_time', $today)
            ->where('status', 'completed')
            ->sum('amount');

        // Get yesterday's earnings for comparison
        $yesterdayEarnings = Earning::whereDate('date_and_time', $yesterday)
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate percentage change
        $earningsChange = 0;
        if ($yesterdayEarnings > 0) {
            $earningsChange = (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        }

        // Get today's transaction count
        $todayTransactions = Earning::whereDate('date_and_time', $today)
            ->where('status', 'completed')
            ->count();

        // Get yesterday's transaction count for comparison
        $yesterdayTransactions = Earning::whereDate('date_and_time', $yesterday)
            ->where('status', 'completed')
            ->count();

        $transactionChange = 0;
        if ($yesterdayTransactions > 0) {
            $transactionChange = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        }

        // Calculate average transaction amount
        $avgTransaction = $todayTransactions > 0 ? $todayEarnings / $todayTransactions : 0;

        // Get recent transactions (latest 10)
        $recentTransactions = Earning::where('status', 'completed')
            ->orderBy('date_and_time', 'desc')
            ->limit(10)
            ->get();

        // Get earnings breakdown by type for today
        $gamingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'gaming')
            ->where('status', 'completed')
            ->sum('amount');

        $browsingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'browsing')
            ->where('status', 'completed')
            ->sum('amount');

        $printingEarnings = Earning::whereDate('date_and_time', $today)
            ->where('type', 'printing')
            ->where('status', 'completed')
            ->sum('amount');

        $totalTodayEarnings = $gamingEarnings + $browsingEarnings + $printingEarnings;

        // Calculate percentages
        $gamingPercent = $totalTodayEarnings > 0 ? ($gamingEarnings / $totalTodayEarnings) * 100 : 0;
        $browsingPercent = $totalTodayEarnings > 0 ? ($browsingEarnings / $totalTodayEarnings) * 100 : 0;
        $printingPercent = $totalTodayEarnings > 0 ? ($printingEarnings / $totalTodayEarnings) * 100 : 0;

        // Get unique terminals with activity today (for machine status)
        $activeTerminals = Earning::whereDate('date_and_time', $today)
            ->distinct()
            ->pluck('terminal')
            ->toArray();

        // Generate machine statuses based on recent activity
        $machines = $this->generateMachineStatuses($activeTerminals);

        // Get activity feed
        $activities = $this->generateActivityFeed($recentTransactions);

        // Get weekly earnings for chart
        $weeklyData = $this->getWeeklyEarningsData();

        return response()->json([
            'success' => true,
            'data' => [
                'todayEarnings' => (float) $todayEarnings,
                'earningsChange' => (float) $earningsChange,
                'todayTransactions' => (int) $todayTransactions,
                'transactionChange' => (float) $transactionChange,
                'avgTransaction' => (float) $avgTransaction,
                'totalTodayEarnings' => (float) $totalTodayEarnings,
                'gamingPercent' => (float) $gamingPercent,
                'browsingPercent' => (float) $browsingPercent,
                'printingPercent' => (float) $printingPercent,
                'machines' => $machines,
                'activities' => $activities,
                'recentTransactions' => $recentTransactions->map(function($t) {
                    return [
                        'terminal' => $t->terminal,
                        'type' => $t->type,
                        'amount' => (float) $t->amount,
                        'date_and_time' => $t->date_and_time->toIso8601String(),
                        'status' => $t->status
                    ];
                }),
                'weeklyEarnings' => $weeklyData['earnings'],
                'weeklyLabels' => $weeklyData['labels']
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
            
            $dayEarnings = Earning::whereDate('date_and_time', $date)
                ->where('status', 'completed')
                ->sum('amount');
            
            $earnings[] = (float) $dayEarnings;
        }
        
        return ['labels' => $labels, 'earnings' => $earnings];
    }

    /**
     * Generate machine statuses based on real PC Units and Sessions
     */
    private function generateMachineStatuses(array $activeTerminals): array
    {
        // Get all PC Units from database
        $pcUnits = PcUnit::orderBy('pc_number', 'asc')->get();
        
        // If no PC units in database, return empty array
        if ($pcUnits->isEmpty()) {
            return [];
        }
        
        // Get active sessions to determine which PCs are in use
        $activeSessions = Session::where('status', 'Active')
            ->get()
            ->keyBy('pc_unit_number');
        
        $machines = [];
        
        foreach ($pcUnits as $pc) {
            $pcNumber = $pc->pc_number;
            $pcNumberFormatted = 'PC-' . str_pad($pcNumber, 2, '0', STR_PAD_LEFT);
            
            // Check if PC has an active session
            $activeSession = $activeSessions->get($pcNumber);
            
            if ($activeSession) {
                // PC is in use
                $status = 'busy';
                // Calculate elapsed time
                $startTime = Carbon::parse($activeSession->start_time);
                $elapsedMinutes = $startTime->diffInMinutes(now());
                $remainingMinutes = $activeSession->remaining_time ?? 0;
                $time = $elapsedMinutes . ':' . str_pad(($remainingMinutes % 60), 2, '0', STR_PAD_LEFT);
            } elseif ($pc->is_active && $pc->status === 'available') {
                // PC is available and active
                $status = 'online';
                $time = '--:--';
            } elseif (!$pc->is_active) {
                // PC is inactive
                $status = 'offline';
                $time = '--:--';
            } else {
                // PC is active but not available (maybe in use without session tracking)
                $status = 'busy';
                $time = '00:00';
            }
            
            $machines[] = [
                'id' => $pcNumberFormatted,
                'status' => $status,
                'time' => $time
            ];
        }
        
        return $machines;
    }

    /**
     * Generate activity feed from recent transactions
     */
    private function generateActivityFeed($transactions): array
    {
        $activities = [];
        $icons = [
            'gaming' => '#00c8ff',
            'browsing' => '#00e676',
            'printing' => '#ffb400'
        ];
        $titles = [
            'gaming' => 'Gaming Session Started',
            'browsing' => 'Browsing Session Started',
            'printing' => 'Print Job Completed'
        ];
        
        foreach ($transactions->take(5) as $txn) {
            $minutesAgo = Carbon::parse($txn->date_and_time)->diffInMinutes(Carbon::now());
            $timeAgo = $minutesAgo < 1 ? 'Just now' : ($minutesAgo < 60 ? $minutesAgo . ' min ago' : Carbon::parse($txn->date_and_time)->format('H:i'));
            
            $activities[] = [
                'dot' => $icons[$txn->type] ?? '#00c8ff',
                'title' => $titles[$txn->type] ?? 'Transaction Completed',
                'sub' => $txn->terminal . ' · ₱' . number_format($txn->amount, 2),
                'time' => $timeAgo
            ];
        }
        
        return $activities;
    }

    /**
     * API endpoint for Dashboard Dataset - returns system summary data
     * 
     * Required Fields:
     * - total_pc_units: Total number of PC units in the system
     * - active_pc_units: Number of active/in-use PCs
     * - available_pc_units: Number of available PCs
     * - total_sessions_today: Total number of sessions today
     * - total_income_today: Total income today
     * - coins_inserted_today: Total coins inserted today
     * - system_status: Overall system status
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
        
        // Get total income today (from Earning model - completed transactions)
        $totalIncomeToday = Earning::whereDate('date_and_time', $today)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Get coins inserted today (from CoinInsert model)
        $coinsInsertedToday = CoinInsert::whereDate('inserted_time', $today)
            ->sum('coin_value');
        
        // Calculate system status based on available metrics
        $systemStatus = $this->calculateSystemStatus($totalPcUnits, $activePcUnits, $totalSessionsToday, $totalIncomeToday);
        
        // Get yesterday's data for comparison
        $yesterday = Carbon::yesterday();
        $yesterdayIncome = Earning::whereDate('date_and_time', $yesterday)
            ->where('status', 'completed')
            ->sum('amount');
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

