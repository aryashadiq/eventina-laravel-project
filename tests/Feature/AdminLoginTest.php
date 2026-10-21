<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('/admin'));
    }

    public function test_admin_can_log_in_after_reset()
    {
        $this->artisan('admin:reset', ['--password' => 'secret123'])->assertSuccessful();

        Livewire::test(Login::class)
            ->set('data.email', 'admin@example.com')
            ->set('data.password', 'secret123')
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect(Filament::getUrl());

        $this->assertAuthenticatedAs(User::where('email', 'admin@example.com')->first());
        $this->get('/admin')->assertOk();
    }

    public function test_regular_user_cannot_open_admin_panel()
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin')->assertForbidden();
    }

    public function test_wrong_password_is_rejected()
    {
        $this->artisan('admin:reset', ['--password' => 'secret123'])->assertSuccessful();

        Livewire::test(Login::class)
            ->set('data.email', 'admin@example.com')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        $this->assertGuest();
    }
}
