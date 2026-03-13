<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Earning;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\EarningsExport;

class EarningsController extends Controller
{
    /**
     * Display earnings data with filters.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = Earning::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('date_and_time', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('date_and_time', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('date_and_time', Carbon::now()->month)
                      ->whereYear('date_and_time', Carbon::now()->year);
                break;
        }

        $earnings = $query->orderBy('date_and_time', 'desc')->get();
        
        // Calculate totals
        $totalAmount = $earnings->sum('amount');
        $totalTransactions = $earnings->count();
        $completedTransactions = $earnings->where('status', 'completed')->count();
        $pendingTransactions = $earnings->where('status', 'pending')->count();

        // Calculate averages
        $averageTransaction = $totalTransactions > 0 ? $totalAmount / $totalTransactions : 0;

        return view('earnings.index', compact(
            'earnings',
            'filter',
            'totalAmount',
            'totalTransactions',
            'completedTransactions',
            'pendingTransactions',
            'averageTransaction'
        ));
    }

    /**
     * Export earnings data to PDF.
     */
    public function exportPDF(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = Earning::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('date_and_time', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('date_and_time', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('date_and_time', Carbon::now()->month)
                      ->whereYear('date_and_time', Carbon::now()->year);
                break;
        }

        $earnings = $query->orderBy('date_and_time', 'desc')->get();
        $totalAmount = $earnings->sum('amount');

        $pdf = PDF::loadView('earnings.pdf', [
            'earnings' => $earnings,
            'filter' => $filter,
            'totalAmount' => $totalAmount,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ]);

        $filename = 'earnings_' . $filter . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export earnings data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $filename = 'earnings_' . $filter . '_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new EarningsExport($filter), $filename);
    }
}

