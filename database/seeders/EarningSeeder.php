<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Earning;
use Carbon\Carbon;

class EarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['gaming', 'browsing', 'printing'];
        $statuses = ['completed', 'completed', 'completed', 'pending']; // 75% completed
        
        // Generate data for the past 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);
            
            // Generate 5-15 transactions per day
            $transactionsPerDay = rand(5, 15);
            
            for ($i = 0; $i < $transactionsPerDay; $i++) {
                $hour = rand(8, 22); // 8 AM to 10 PM
                $minute = rand(0, 59);
                $second = rand(0, 59);
                
                $transactionDate = $date->copy()->setTime($hour, $minute, $second);
                
                // Random amount between 5 and 100
                $amount = rand(5, 100);
                
                // Add some variation for different session types
                $type = $types[array_rand($types)];
                
                Earning::create([
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'terminal' => 'PC-' . str_pad(rand(1, 20), 2, '0', STR_PAD_LEFT),
                    'type' => $type,
                    'date_and_time' => $transactionDate,
                    'amount' => $amount + (rand(0, 99) / 100), // Add cents
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}

