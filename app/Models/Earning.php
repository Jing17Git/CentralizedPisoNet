<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'transaction_id',
        'terminal',
        'type',
        'date_and_time',
        'amount',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_and_time' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Scope for daily earnings
     */
    public function scopeDaily($query)
    {
        return $query->whereDate('date_and_time', now()->toDateString());
    }

    /**
     * Scope for weekly earnings
     */
    public function scopeWeekly($query)
    {
        return $query->whereBetween('date_and_time', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for monthly earnings
     */
    public function scopeMonthly($query)
    {
        return $query->whereMonth('date_and_time', now()->month)
                     ->whereYear('date_and_time', now()->year);
    }

    /**
     * Get total earnings based on filter
     */
    public static function getTotalByFilter($filter = 'daily')
    {
        return self::{$filter}()->sum('amount');
    }

    /**
     * Get earnings count based on filter
     */
    public static function getCountByFilter($filter = 'daily')
    {
        return self::{$filter}()->count();
    }
}

