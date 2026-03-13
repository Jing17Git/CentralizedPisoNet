<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pc_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'session_id',
        'pc_unit_number',
        'user_session_name',
        'start_time',
        'remaining_time',
        'status',
        'end_time',
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
            'remaining_time' => 'integer',
        ];
    }

    /**
     * Scope for active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for ended sessions.
     */
    public function scopeEnded($query)
    {
        return $query->where('status', 'Ended');
    }

    /**
     * Check if session is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * End the session.
     */
    public function endSession(): bool
    {
        return $this->update([
            'status' => 'Ended',
            'end_time' => now(),
            'remaining_time' => 0,
        ]);
    }

    /**
     * Get remaining time in human readable format.
     */
    public function getRemainingTimeFormattedAttribute(): string
    {
        if ($this->remaining_time <= 0) {
            return '00:00';
        }

        $hours = floor($this->remaining_time / 60);
        $minutes = $this->remaining_time % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Get session duration.
     */
    public function getDurationAttribute(): string
    {
        if (!$this->start_time) {
            return '00:00';
        }

        $endTime = $this->end_time ?? now();
        $diff = $this->start_time->diff($endTime);

        return sprintf('%02d:%02d', $diff->h, $diff->i);
    }

    /**
     * Generate a unique session ID.
     */
    public static function generateSessionId(): string
    {
        return 'SES-' . strtoupper(uniqid()) . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    }
}

