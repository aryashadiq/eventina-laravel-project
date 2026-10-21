<?php

namespace Database\Seeders;

use App\Models\Organizer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OrganizerSeeder extends Seeder
{
    public function run()
    {
        $org = Organizer::firstOrCreate([
            'email' => 'organizer@example.com'
        ], [
            'name' => 'Organizer',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'approved' => true,
        ]);

        $role = Role::firstOrCreate(['name' => 'organizer']);
        $org->assignRole($role);
    }
}
