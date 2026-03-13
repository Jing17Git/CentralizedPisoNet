<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcUnit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'pc_number',
        'branch_id',
        'ip_address',
        'status',
        'is_active',
        'last_activity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_activity' => 'datetime',
        ];
    }

    /**
     * Scope for active PC units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for available PC units
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => 'green',
            'in_use' => 'yellow',
            'offline' => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if PC is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Toggle active status
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }
}

