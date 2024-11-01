<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calendar;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            Calendar::create([
                'name' => 'Ibadah',
                'color' => '#dbeafe',
            ]);
            Calendar::create([
                'name' => 'Non Ibadah',
                'color' => '#ecfccb',
            ]);
    }
}
