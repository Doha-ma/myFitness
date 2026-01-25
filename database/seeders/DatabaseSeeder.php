<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::firstOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Receptionist
        User::firstOrCreate(
            ['email' => 'receptionist@gym.com'],
            [
                'name' => 'Réceptionniste Principal',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
            ]
        );

        // Create Coach
        User::firstOrCreate(
            ['email' => 'coach@gym.com'],
            [
                'name' => 'Coach Principal',
                'password' => Hash::make('password'),
                'role' => 'coach',
            ]
        );
    }
}