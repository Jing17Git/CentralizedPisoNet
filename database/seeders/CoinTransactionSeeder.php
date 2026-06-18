<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoinTransaction;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoinTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('coin_transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $machines = Machine::all();

        if ($machines->isEmpty()) {
            $this->command->warn('No machines found. Please run MachineSeeder first.');
            return;
        }

        // Generate transactions for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Random number of transactions per day (5-15)
            $transactionsPerDay = rand(5, 15);
            
            for ($j = 0; $j < $transactionsPerDay; $j++) {
                $machine = $machines->random();
                $coinsInserted = rand(1, 10); // 1-10 coins
                $minutesPurchased = $coinsInserted * 5; // 5 minutes per coin
                $amount = $coinsInserted * 5; // ₱5 per coin
                
                CoinTransaction::create([
                    'machine_id' => $machine->id,
                    'coins_inserted' => $coinsInserted,
                    'minutes_purchased' => $minutesPurchased,
                    'amount' => $amount,
                    'created_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);
            }
        }

        $this->command->info('Coin transactions seeded successfully!');
    }
}
