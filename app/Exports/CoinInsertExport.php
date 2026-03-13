<?php

namespace App\Exports;

use App\Models\CoinInsert;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CoinInsertExport implements FromCollection, WithHeadings, WithMapping
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
        $query = CoinInsert::query();

        switch ($this->filter) {
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

        return $query->orderBy('inserted_time', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Coin ID',
            'PC Unit ID',
            'Coin Value',
            'Minutes Given',
            'Inserted Time',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->coin_id,
            $row->pc_unit_id,
            '₱' . number_format($row->coin_value, 2),
            $row->minutes_given . ' min',
            $row->inserted_time ? $row->inserted_time->format('Y-m-d H:i:s') : '-',
        ];
    }
}

