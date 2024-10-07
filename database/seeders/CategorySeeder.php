<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Sunnah',
            'color' => '#dbeafe'
        ]);
        Category::create([
            'name' => 'Wajib',
            'color' => '#fee2e2'
        ]);
        Category::create([
            'name' => 'Work',
            'color' => '#dcfce7'
        ]);
        Category::create([
            'name' => 'Study',
            'color' => '#fef9c3'
        ]);
        Category::create([
            'name' => 'Health',
            'color' => '#e0e7ff'
        ]);
        Category::create([
            'name' => 'Finance',
            'color' => '#cffafe'
        ]);
        Category::create([
            'name' => 'Shopping',
            'color' => '#ffedd5'
        ]);
        Category::create([
            'name' => 'Travel',
            'color' => '#f3e8ff'
        ]);
        Category::create([
            'name' => 'Personal',
            'color' => '#ccfbf1'
        ]);
        Category::create([
            'name' => 'other',
            'color' => '#f3f4f6'
        ]);
    }
}
