<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $guards = ['web', 'organizer'];
        foreach (['admin','organizer','user'] as $role) {
            foreach ($guards as $guard) {
                Role::firstOrCreate(['name' => $role, 'guard_name' => $guard]);
            }
        }
    }
}
