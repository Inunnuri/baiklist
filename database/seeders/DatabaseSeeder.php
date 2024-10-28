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
        // User::factory(10)->create();

        $this->call([
            CalendarSeeder::class, CategorySeeder::class, StatusSeeder::class, FrequencySeeder::class,
        ]);
    }
}
