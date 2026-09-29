<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $roles = [
            ['name' => 'User', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Seller', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($roles as $role) {
            if (!DB::table('roles')->where('name', $role['name'])->exists()) {
                DB::table('roles')->insert($role);
            }
        }
    }
}
