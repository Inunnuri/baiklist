<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'name' => 'Plan',
            'color' => '#fee2e2'
        ]);
        Status::create([
            'name' => 'Progress',
            'color' => '#dbeafe'
        ]);
        Status::create([
            'name' => 'Completed',
            'color' => '#cffafe'
        ]);
    }
}
