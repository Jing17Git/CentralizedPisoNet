<?php

namespace App\Exports;

use App\Models\Earning;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EarningsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Earning::query();

        switch ($this->filter) {
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

        return $query->orderBy('date_and_time', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Transaction ID',
            'Terminal',
            'Type',
            'Date & Time',
            'Amount',
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
            $row->terminal,
            ucfirst($row->type),
            $row->date_and_time->format('Y-m-d H:i:s'),
            '₱' . number_format($row->amount, 2),
            ucfirst($row->status),
        ];
    }
}

