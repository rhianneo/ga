<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all your custom seeders here
        $this->call([
            UserSeeder::class, // Seeds GA Staff, Admin Expatriate, Expatriate
        ]);
    }
}
