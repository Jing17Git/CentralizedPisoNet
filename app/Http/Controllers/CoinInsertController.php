<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoinInsert;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\CoinInsertExport;

class CoinInsertController extends Controller
{
    /**
     * Display coin inserts data with filters.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = CoinInsert::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('inserted_time', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('inserted_time', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('inserted_time', Carbon::now()->month)
                      ->whereYear('inserted_time', Carbon::now()->year);
                break;
        }

        $coinInserts = $query->orderBy('inserted_time', 'desc')->get();
        
        // Calculate totals
        $totalCoinValue = $coinInserts->sum('coin_value');
        $totalMinutes = $coinInserts->sum('minutes_given');
        $totalInserts = $coinInserts->count();

        // Calculate averages
        $averageCoinValue = $totalInserts > 0 ? $totalCoinValue / $totalInserts : 0;
        $averageMinutes = $totalInserts > 0 ? $totalMinutes / $totalInserts : 0;

        return view('coininserts.index', compact(
            'coinInserts',
            'filter',
            'totalCoinValue',
            'totalMinutes',
            'totalInserts',
            'averageCoinValue',
            'averageMinutes'
        ));
    }

    /**
     * Export coin inserts data to PDF.
     */
    public function exportPDF(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $query = CoinInsert::query();

        switch ($filter) {
            case 'daily':
                $query->whereDate('inserted_time', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('inserted_time', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('inserted_time', Carbon::now()->month)
                      ->whereYear('inserted_time', Carbon::now()->year);
                break;
        }

        $coinInserts = $query->orderBy('inserted_time', 'desc')->get();
        $totalCoinValue = $coinInserts->sum('coin_value');
        $totalMinutes = $coinInserts->sum('minutes_given');

        $pdf = PDF::loadView('coininserts.pdf', [
            'coinInserts' => $coinInserts,
            'filter' => $filter,
            'totalCoinValue' => $totalCoinValue,
            'totalMinutes' => $totalMinutes,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ]);

        $filename = 'coin_inserts_' . $filter . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export coin inserts data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $filename = 'coin_inserts_' . $filter . '_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new CoinInsertExport($filter), $filename);
    }

    /**
     * Store a new coin insert record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_unit_id' => 'required|string|max:50',
            'coin_value' => 'required|numeric|min:0.01',
            'minutes_given' => 'required|integer|min:1',
        ]);

        $validated['coin_id'] = CoinInsert::generateCoinId();
        $validated['inserted_time'] = Carbon::now();

        $coinInsert = CoinInsert::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Coin insert recorded successfully!',
            'coinInsert' => $coinInsert,
        ]);
    }
}

