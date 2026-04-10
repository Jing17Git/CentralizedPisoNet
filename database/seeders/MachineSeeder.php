<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('machines')->truncate();

        $machines = [
            [
                'machine_name' => 'PisoNet Station 1',
                'machine_code' => 'PC-01',
                'ip_address' => '192.168.1.101',
                'location' => 'Main Floor - Left Side',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'machine_name' => 'PisoNet Station 2',
                'machine_code' => 'PC-02',
                'ip_address' => '192.168.1.102',
                'location' => 'Main Floor - Center',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'machine_name' => 'PisoNet Station 3',
                'machine_code' => 'PC-03',
                'ip_address' => '192.168.1.103',
                'location' => 'Main Floor - Right Side',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'machine_name' => 'PisoNet Station 4',
                'machine_code' => 'PC-04',
                'ip_address' => '192.168.1.104',
                'location' => 'Second Floor - Gaming Area',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'machine_name' => 'PisoNet Station 5',
                'machine_code' => 'PC-05',
                'ip_address' => '192.168.1.105',
                'location' => 'Second Floor - Study Area',
                'status' => 'offline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($machines as $machine) {
            Machine::create($machine);
        }

        $this->command->info('Machines seeded successfully!');
    }
}
