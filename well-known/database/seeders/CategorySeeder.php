<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insertOrIgnore([
            ['id' => 1, 'name' => 'Electronics', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Hotels & Resorts', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Top Deals', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Computers', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Monitors', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Mouses', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Electronics More 1', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Electronics More 2', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Electronics More 3', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
