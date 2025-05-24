<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['id' => 1, 'name' => 'Analysis'],
            ['id' => 2, 'name' => 'Development'],
            ['id' => 3, 'name' => 'Maintenance'],
        ]);
    }
}
