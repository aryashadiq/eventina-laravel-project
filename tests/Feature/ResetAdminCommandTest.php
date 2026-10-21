<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_admin_when_missing()
    {
        $this->artisan('admin:reset', ['email' => 'boss@example.com', '--password' => 'secret123'])
            ->assertSuccessful();

        $user = User::where('email', 'boss@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_resets_existing_password_and_restores_admin_role()
    {
        $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'oldpassword']);

        $this->artisan('admin:reset', ['--password' => 'newpassword'])
            ->assertSuccessful();

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_generates_password_when_non_interactive()
    {
        $this->artisan('admin:reset', ['--no-interaction' => true])
            ->expectsOutputToContain('Generated password:')
            ->assertSuccessful();

        $this->assertTrue(User::where('email', 'admin@example.com')->first()->hasRole('admin'));
    }

    public function test_rejects_short_password()
    {
        $this->artisan('admin:reset', ['--password' => 'short'])
            ->assertFailed();

        $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    }
}
