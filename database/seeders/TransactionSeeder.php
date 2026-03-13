<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'transaction_id' => 'TXN-A1B2C3D4E5-001',
                'pc_unit_id' => 'PC-001',
                'total_coins' => 50.00,
                'total_minutes' => 60,
                'start_time' => Carbon::now()->subHours(3),
                'end_time' => Carbon::now()->subHours(2),
                'transaction_date' => Carbon::today(),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-F6G7H8I9J0-002',
                'pc_unit_id' => 'PC-002',
                'total_coins' => 100.00,
                'total_minutes' => 120,
                'start_time' => Carbon::now()->subHours(2),
                'end_time' => Carbon::now()->subHours(1),
                'transaction_date' => Carbon::today(),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-K1L2M3N4O5-003',
                'pc_unit_id' => 'PC-003',
                'total_coins' => 25.00,
                'total_minutes' => 30,
                'start_time' => Carbon::now()->subMinutes(45),
                'end_time' => null,
                'transaction_date' => Carbon::today(),
                'status' => 'active',
            ],
            [
                'transaction_id' => 'TXN-P6Q7R8S9T0-004',
                'pc_unit_id' => 'PC-001',
                'total_coins' => 75.00,
                'total_minutes' => 90,
                'start_time' => Carbon::now()->subDays(1)->subHours(5),
                'end_time' => Carbon::now()->subDays(1)->subHours(3.5),
                'transaction_date' => Carbon::yesterday(),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-U1V2W3X4Y5-005',
                'pc_unit_id' => 'PC-004',
                'total_coins' => 150.00,
                'total_minutes' => 180,
                'start_time' => Carbon::now()->subDays(1)->subHours(2),
                'end_time' => Carbon::now()->subDays(1),
                'transaction_date' => Carbon::yesterday(),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-Z6A7B8C9D0-006',
                'pc_unit_id' => 'PC-002',
                'total_coins' => 40.00,
                'total_minutes' => 45,
                'start_time' => Carbon::now()->subDays(3)->subHours(4),
                'end_time' => Carbon::now()->subDays(3)->subHours(3.25),
                'transaction_date' => Carbon::now()->subDays(3),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-E1F2G3H4I5-007',
                'pc_unit_id' => 'PC-005',
                'total_coins' => 200.00,
                'total_minutes' => 240,
                'start_time' => Carbon::now()->subDays(5)->subHours(6),
                'end_time' => Carbon::now()->subDays(5)->subHours(2),
                'transaction_date' => Carbon::now()->subDays(5),
                'status' => 'completed',
            ],
            [
                'transaction_id' => 'TXN-J6K7L8M9N0-008',
                'pc_unit_id' => 'PC-003',
                'total_coins' => 60.00,
                'total_minutes' => 75,
                'start_time' => Carbon::now()->subDays(7)->subHours(3),
                'end_time' => Carbon::now()->subDays(7)->subHours(1.75),
                'transaction_date' => Carbon::now()->subDays(7),
                'status' => 'completed',
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}

