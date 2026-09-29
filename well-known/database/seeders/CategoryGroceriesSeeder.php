<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryGroceriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insertOrIgnore([
            ['id' => 13, 'name' => 'Cooking Oil', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Rice & Grains', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'Spices & Masala', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Snacks & Biscuits', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
