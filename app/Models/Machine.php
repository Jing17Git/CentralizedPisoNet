<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name',
        'machine_code',
        'ip_address',
        'location',
        'status'
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class, 'pc_unit_number', 'machine_code');
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }
}
