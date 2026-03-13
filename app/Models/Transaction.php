<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'transaction_id',
        'pc_unit_id',
        'total_coins',
        'total_minutes',
        'start_time',
        'end_time',
        'transaction_date',
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
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'transaction_date' => 'date',
            'total_coins' => 'decimal:2',
            'total_minutes' => 'integer',
        ];
    }

    /**
     * Scope for daily transactions
     */
    public function scopeDaily($query)
    {
        return $query->whereDate('transaction_date', now()->toDateString());
    }

    /**
     * Scope for weekly transactions
     */
    public function scopeWeekly($query)
    {
        return $query->whereBetween('transaction_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
    }

    /**
     * Scope for monthly transactions
     */
    public function scopeMonthly($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                     ->whereYear('transaction_date', now()->year);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for active transactions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Generate a unique transaction ID.
     */
    public static function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get total coins based on filter
     */
    public static function getTotalCoinsByFilter($filter = 'daily')
    {
        return self::{$filter}()->sum('total_coins');
    }

    /**
     * Get total minutes based on filter
     */
    public static function getTotalMinutesByFilter($filter = 'daily')
    {
        return self::{$filter}()->sum('total_minutes');
    }

    /**
     * Get transaction count based on filter
     */
    public static function getCountByFilter($filter = 'daily')
    {
        return self::{$filter}()->count();
    }
}

