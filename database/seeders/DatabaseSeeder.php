<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Core seeders needed for admin/organizer accounts
        $this->call([
            \Database\Seeders\RolesTableSeeder::class,
            \Database\Seeders\AdminSeeder::class,
            \Database\Seeders\OrganizerSeeder::class,
        ]);

        // Create a test user as well
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
