<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@centralizedpisonet.local',
            'password' => Hash::make('Ch@ngeMe_OnDeployment!'),
            'role'     => 'admin',
        ]);
    }
}
