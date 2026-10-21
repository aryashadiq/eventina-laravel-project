<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organizer;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_password_reset_request_and_reset()
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RolesTableSeeder']);
        $user = User::create(['name'=>'U','email'=>'u@example.com','password'=>bcrypt('oldpass')]);

        $resp = $this->post(route('password.email'), ['email'=>'u@example.com']);
        $resp->assertSessionHas('status');

        // simulate token generation and reset
        $token = Password::broker('users')->createToken($user);
        $reset = $this->post(route('password.update'), ['token'=>$token,'email'=>'u@example.com','password'=>'newpass','password_confirmation'=>'newpass']);
        $reset->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('newpass', $user->fresh()->password));
    }

    public function test_organizer_password_reset_request_and_reset()
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RolesTableSeeder']);
        $org = Organizer::create(['name'=>'O','email'=>'o@example.com','password'=>bcrypt('oldpass'),'approved'=>true]);

        $resp = $this->post(route('organizers.password.email'), ['email'=>'o@example.com']);
        $resp->assertSessionHas('status');

        $token = Password::broker('organizers')->createToken($org);
        $reset = $this->post(route('organizers.password.update'), ['token'=>$token,'email'=>'o@example.com','password'=>'newpass','password_confirmation'=>'newpass']);
        $reset->assertRedirect(route('organizers.login'));
        $this->assertTrue(Hash::check('newpass', $org->fresh()->password));
    }
}
