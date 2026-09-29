<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryCosmeticsSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insertOrIgnore([
            ['id' => 17, 'name' => 'Skincare', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Makeup', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'name' => 'Haircare', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'name' => 'Other Cosmetics', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
