<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           // Role Management
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           // User Management
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           // Product Management
           'product-list',
           'product-create',
           'product-edit',
           'product-delete',
           // Category Management
           'category-list',
           'category-create',
           'category-edit',
           'category-delete',
           // Order Management
           'order-list',
           'order-create',
           'order-edit',
           'order-delete',
           // Menu Management
           'menu-list',
           'menu-create',
           'menu-edit',
           'menu-delete',
           // Sell Report
           'sell-report-list',
           'sell-report-create',
           'sell-report-edit',
           'sell-report-delete',
        ];
   
        foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }
    }
}
