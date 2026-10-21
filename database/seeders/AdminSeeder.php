<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $role = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
        $user->assignRole($role);
    }
}
