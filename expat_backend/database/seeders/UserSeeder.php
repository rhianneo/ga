<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // GA Staff user
        User::updateOrCreate(
            ['email' => 'lhian.muit.ph@toyoflex.com'],
            [
                'name' => 'Lhian Muit',
                'password' => Hash::make('secret123'), // Change to strong password in production
                'role' => 'GA Staff',
            ]
        );

        // Admin Expatriate user
        User::updateOrCreate(
            ['email' => 'jane.doe@asahi-intecc.com'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('secret123'),
                'role' => 'Admin Expatriate',
            ]
        );

        // Expatriate user
        User::updateOrCreate(
            ['email' => 'john.doe@asahi-intecc.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('secret123'),
                'role' => 'Expatriate',
            ]
        );
    }
}
