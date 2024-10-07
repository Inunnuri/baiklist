<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Frequency;


class FrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Frequency::create([
            'name' => 'none',
            'color' => '#f3f4f6',
        ]);
        Frequency::create([
            'name' => 'daily',
            'color' => '#dbeafe',
        ]);
        Frequency::create([
            'name' => 'weekly',
            'color' => '#cffafe',
        ]);
        Frequency::create([
            'name' => 'monthly',
            'color' => '#ffedd5',
        ]);
        Frequency::create([
            'name' => 'yearly',
            'color' => '#fee2e2',
        ]);
    }
}
