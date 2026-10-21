<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organizer;
use App\Models\Ticket;

class WizardAndBuyTest extends TestCase
{
    use RefreshDatabase;

    public function test_wizard_creates_event_and_ticket_and_buy_creates_invoice()
    {
        // seed roles and organizer
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RolesTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\OrganizerSeeder']);

        $organizer = Organizer::first();

        // simulate organizer starting wizard
        $this->actingAs($organizer, 'organizer');

        $resp = $this->get(route('organizers.events.wizard.start'));
        $resp->assertRedirect();

        $this->post(route('organizers.events.wizard.post', ['step' => 'general']), [
            'title' => 'Test Event',
            'description' => 'Desc'
        ]);

        $this->post(route('organizers.events.wizard.post', ['step' => 'subevents']), [
            'subevents' => [[ 'title' => 'Sub1', 'start_at' => now(), 'end_at' => now()->addHour() ]]
        ]);

        $this->post(route('organizers.events.wizard.post', ['step' => 'tickets']), [
            'tickets' => [[ 'code' => 'T100', 'price' => 10000, 'currency' => 'IDR' ]]
        ]);

        $this->post(route('organizers.events.wizard.post', ['step' => 'support']), [
            'contact_name' => 'Support', 'contact_email' => 's@example.com'
        ])->assertRedirect(route('organizers.dashboard'));

    $ticket = Ticket::first();
    $this->assertNotNull($ticket);

    // create a normal user and act as them for purchase
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    // buy
    $resp = $this->post(route('tickets.buy', ['ticket' => $ticket->id]));
    $resp->assertRedirect();

    // invoice created
    $this->assertDatabaseHas('invoices', ['ticket_id' => $ticket->id, 'user_id' => $user->id]);
    }
}
