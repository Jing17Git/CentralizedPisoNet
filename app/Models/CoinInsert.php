<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinInsert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'coin_id',
        'pc_unit_id',
        'coin_value',
        'minutes_given',
        'inserted_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'inserted_time' => 'datetime',
            'coin_value' => 'decimal:2',
            'minutes_given' => 'integer',
        ];
    }

    /**
     * Scope for daily coin inserts
     */
    public function scopeDaily($query)
    {
        return $query->whereDate('inserted_time', now()->toDateString());
    }

    /**
     * Scope for weekly coin inserts
     */
    public function scopeWeekly($query)
    {
        return $query->whereBetween('inserted_time', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for monthly coin inserts
     */
    public function scopeMonthly($query)
    {
        return $query->whereMonth('inserted_time', now()->month)
                     ->whereYear('inserted_time', now()->year);
    }

    /**
     * Generate a unique coin ID.
     */
    public static function generateCoinId(): string
    {
        return 'COIN-' . strtoupper(uniqid()) . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get total coin value based on filter
     */
    public static function getTotalValueByFilter($filter = 'daily')
    {
        return self::{$filter}()->sum('coin_value');
    }

    /**
     * Get total minutes given based on filter
     */
    public static function getTotalMinutesByFilter($filter = 'daily')
    {
        return self::{$filter}()->sum('minutes_given');
    }

    /**
     * Get coin insert count based on filter
     */
    public static function getCountByFilter($filter = 'daily')
    {
        return self::{$filter}()->count();
    }
}

