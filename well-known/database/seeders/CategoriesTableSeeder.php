<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Electronics', 'status' => 0],
            ['id' => 5, 'name' => 'Top Deals', 'status' => 0],
            ['id' => 6, 'name' => 'Computers', 'status' => 0],
            ['id' => 7, 'name' => 'Monitors', 'status' => 0],
            ['id' => 8, 'name' => 'Mouse', 'status' => 0],
        ]);
    }
}
