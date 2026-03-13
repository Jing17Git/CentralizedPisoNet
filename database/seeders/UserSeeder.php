<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Account
        User::updateOrCreate(
            ['email' => 'admin@pisonet.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@pisonet.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin account created successfully!');
        $this->command->info('Email: admin@pisonet.com');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('You can now change the password from the dashboard.');
    }
}

