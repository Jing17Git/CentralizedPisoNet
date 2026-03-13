<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Machine;
use App\Models\CoinTransaction;
use App\Models\Session;
use Carbon\Carbon;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample machines only if they don't exist
        if (Machine::count() == 0) {
            $machines = [
                ['machine_name' => 'PC Unit 01', 'machine_code' => 'PC-001', 'ip_address' => '192.168.1.101', 'location' => 'Floor 1', 'status' => 'online'],
                ['machine_name' => 'PC Unit 02', 'machine_code' => 'PC-002', 'ip_address' => '192.168.1.102', 'location' => 'Floor 1', 'status' => 'online'],
                ['machine_name' => 'PC Unit 03', 'machine_code' => 'PC-003', 'ip_address' => '192.168.1.103', 'location' => 'Floor 2', 'status' => 'offline'],
                ['machine_name' => 'PC Unit 04', 'machine_code' => 'PC-004', 'ip_address' => '192.168.1.104', 'location' => 'Floor 2', 'status' => 'online'],
                ['machine_name' => 'PC Unit 05', 'machine_code' => 'PC-005', 'ip_address' => '192.168.1.105', 'location' => 'Floor 3', 'status' => 'online'],
            ];

            foreach ($machines as $machineData) {
                Machine::create($machineData);
            }
        }

        // Create sample coin transactions for the last 7 days
        if (CoinTransaction::count() == 0) {
            $machineIds = Machine::pluck('id')->toArray();
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $transactionsPerDay = rand(5, 15);
                
                for ($j = 0; $j < $transactionsPerDay; $j++) {
                    $coins = rand(1, 10);
                    $minutes = $coins * 5; // 5 minutes per coin
                    $amount = $coins * 5; // ₱5 per coin
                    
                    CoinTransaction::create([
                        'machine_id' => $machineIds[array_rand($machineIds)],
                        'coins_inserted' => $coins,
                        'minutes_purchased' => $minutes,
                        'amount' => $amount,
                        'created_at' => $date->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    ]);
                }
            }
        }

        // Create sample sessions
        if (\DB::table('pc_sessions')->count() == 0) {
            for ($i = 0; $i < 20; $i++) {
                $startTime = Carbon::now()->subDays(rand(0, 7))->subHours(rand(1, 12));
                $remainingTime = rand(0, 60);
                $status = $remainingTime > 0 ? 'Active' : 'Ended';
                $endTime = $status == 'Ended' ? $startTime->copy()->addMinutes(rand(30, 120)) : null;
                
                \DB::table('pc_sessions')->insert([
                    'session_id' => 'SES-' . strtoupper(uniqid()),
                    'pc_unit_number' => 'PC-' . str_pad(rand(1, 5), 3, '0', STR_PAD_LEFT),
                    'user_session_name' => 'User' . rand(100, 999),
                    'start_time' => $startTime,
                    'remaining_time' => $remainingTime,
                    'status' => $status,
                    'end_time' => $endTime,
                    'created_at' => $startTime,
                    'updated_at' => $startTime,
                ]);
            }
        }
    }
}
