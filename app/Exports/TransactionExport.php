<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;

    public function __construct($filter = 'daily')
    {
        $this->filter = $filter;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Transaction::query();

        switch ($this->filter) {
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

        return $query->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Transaction ID',
            'PC Unit ID',
            'Total Coins',
            'Total Minutes',
            'Start Time',
            'End Time',
            'Transaction Date',
            'Status',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->transaction_id,
            $row->pc_unit_id,
            '₱' . number_format($row->total_coins, 2),
            $row->total_minutes . ' min',
            $row->start_time ? $row->start_time->format('Y-m-d H:i:s') : '-',
            $row->end_time ? $row->end_time->format('Y-m-d H:i:s') : '-',
            $row->transaction_date->format('Y-m-d'),
            ucfirst($row->status),
        ];
    }
}

