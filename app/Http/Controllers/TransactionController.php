<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\TransactionExport;

class TransactionController extends Controller
{
    /**
     * Display transactions data with filters.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = Transaction::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('transaction_date', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('transaction_date', [
                    Carbon::now()->startOfWeek()->toDateString(),
                    Carbon::now()->endOfWeek()->toDateString()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('transaction_date', Carbon::now()->month)
                      ->whereYear('transaction_date', Carbon::now()->year);
                break;
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc')->get();
        
        // Calculate totals
        $totalCoins = $transactions->sum('total_coins');
        $totalMinutes = $transactions->sum('total_minutes');
        $totalTransactions = $transactions->count();
        $completedTransactions = $transactions->where('status', 'completed')->count();
        $activeTransactions = $transactions->where('status', 'active')->count();

        // Calculate averages
        $averageCoins = $totalTransactions > 0 ? $totalCoins / $totalTransactions : 0;
        $averageMinutes = $totalTransactions > 0 ? $totalMinutes / $totalTransactions : 0;

        // Get chart data
        $chartData = $this->getChartData($filter);

        return view('transactions.index', compact(
            'transactions',
            'filter',
            'totalCoins',
            'totalMinutes',
            'totalTransactions',
            'completedTransactions',
            'activeTransactions',
            'averageCoins',
            'averageMinutes',
            'chartData'
        ));
    }

    /**
     * Get chart data based on filter
     */
    private function getChartData($filter)
    {
        $labels = [];
        $earningsData = [];
        $transactionCountData = [];

        switch ($filter) {
            case 'daily':
                // Last 7 days for daily view
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('M d');
                    
                    $dayTransactions = Transaction::whereDate('transaction_date', $date->toDateString())->get();
                    $earningsData[] = $dayTransactions->sum('total_coins');
                    $transactionCountData[] = $dayTransactions->count();
                }
                break;
            case 'weekly':
                // Last 4 weeks for weekly view
                for ($i = 3; $i >= 0; $i--) {
                    $weekStart = Carbon::now()->startOfWeek()->subWeeks($i);
                    $weekEnd = Carbon::now()->endOfWeek()->subWeeks($i);
                    $labels[] = 'Week ' . ($i + 1);
                    
                    $weekTransactions = Transaction::whereBetween('transaction_date', [
                        $weekStart->toDateString(),
                        $weekEnd->toDateString()
                    ])->get();
                    $earningsData[] = $weekTransactions->sum('total_coins');
                    $transactionCountData[] = $weekTransactions->count();
                }
                break;
            case 'monthly':
                // Last 6 months for monthly view
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    
                    $monthTransactions = Transaction::whereMonth('transaction_date', $date->month)
                        ->whereYear('transaction_date', $date->year)
                        ->get();
                    $earningsData[] = $monthTransactions->sum('total_coins');
                    $transactionCountData[] = $monthTransactions->count();
                }
                break;
        }

        // Get status breakdown for donut chart
        $allTransactions = Transaction::query();
        switch ($filter) {
            case 'daily':
                $allTransactions->whereDate('transaction_date', Carbon::today());
                break;
            case 'weekly':
                $allTransactions->whereBetween('transaction_date', [
                    Carbon::now()->startOfWeek()->toDateString(),
                    Carbon::now()->endOfWeek()->toDateString()
                ]);
                break;
            case 'monthly':
                $allTransactions->whereMonth('transaction_date', Carbon::now()->month)
                      ->whereYear('transaction_date', Carbon::now()->year);
                break;
        }
        $statusData = [
            'completed' => $allTransactions->clone()->where('status', 'completed')->count(),
            'active' => $allTransactions->clone()->where('status', 'active')->count(),
            'cancelled' => $allTransactions->clone()->where('status', 'cancelled')->count(),
        ];

        return [
            'labels' => $labels,
            'earningsData' => $earningsData,
            'transactionCountData' => $transactionCountData,
            'statusData' => $statusData,
        ];
    }

    /**
     * Export transactions data to PDF.
     */
    public function exportPDF(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = Transaction::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('transaction_date', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('transaction_date', [
                    Carbon::now()->startOfWeek()->toDateString(),
                    Carbon::now()->endOfWeek()->toDateString()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('transaction_date', Carbon::now()->month)
                      ->whereYear('transaction_date', Carbon::now()->year);
                break;
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc')->get();
        $totalCoins = $transactions->sum('total_coins');
        $totalMinutes = $transactions->sum('total_minutes');

        $pdf = PDF::loadView('transactions.pdf', [
            'transactions' => $transactions,
            'filter' => $filter,
            'totalCoins' => $totalCoins,
            'totalMinutes' => $totalMinutes,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ]);

        $filename = 'transactions_' . $filter . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export transactions data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $filename = 'transactions_' . $filter . '_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new TransactionExport($filter), $filename);
    }

    /**
     * Store a new transaction (when payment is inserted).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_unit_id' => 'required|string|max:50',
            'total_coins' => 'required|numeric|min:0.01',
            'total_minutes' => 'required|integer|min:1',
        ]);

        $validated['transaction_id'] = Transaction::generateTransactionId();
        $validated['start_time'] = Carbon::now();
        $validated['transaction_date'] = Carbon::now()->toDateString();
        $validated['status'] = 'active';

        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction recorded successfully!',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Complete a transaction (when session ends).
     */
    public function complete(Transaction $transaction)
    {
        if ($transaction->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction is not active!',
            ], 400);
        }

        $transaction->update([
            'end_time' => Carbon::now(),
            'status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction completed successfully!',
            'transaction' => $transaction,
        ]);
    }
}

