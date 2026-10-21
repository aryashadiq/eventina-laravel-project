<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrganizerRegistered;
use App\Mail\OrganizerApproved;
use App\Models\Organizer;
use App\Models\User;

class OrganizerApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_notification_and_requires_approval()
    {
        Mail::fake();
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RolesTableSeeder']);

        $resp = $this->post(route('organizers.register.post'), [
            'name' => 'New Org',
            'email' => 'neworg@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $resp->assertRedirect(route('organizers.login'));
        $this->assertDatabaseHas('organizers', ['email' => 'neworg@example.com','approved' => false]);
        Mail::assertSent(OrganizerRegistered::class);
    }

    public function test_admin_can_approve_and_send_email()
    {
        Mail::fake();
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RolesTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\AdminSeeder']);

        $org = Organizer::create(['name'=>'X','email'=>'x@example.com','password'=>bcrypt('password'),'approved'=>false]);

        $admin = User::where('email','admin@example.com')->first();
        $this->actingAs($admin);

        $resp = $this->post(route('backend.organizers.approve', ['id' => $org->id]));
        $resp->assertRedirect(route('backend.organizers.pending'));
        $this->assertDatabaseHas('organizers', ['id'=>$org->id,'approved'=>true]);
        Mail::assertSent(OrganizerApproved::class);
    }
}
