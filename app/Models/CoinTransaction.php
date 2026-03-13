<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $fillable = [
        'machine_id',
        'coins_inserted',
        'minutes_purchased',
        'amount'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
