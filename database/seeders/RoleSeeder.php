<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create 'user' role for regular users
        Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web'],
            ['name' => 'user', 'guard_name' => 'web']
        );

        // Create 'admin' role for administrators
        Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );
    }
}
