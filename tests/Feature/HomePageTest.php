<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(string $title, string $status, $startAt, int $price = 50000): Event
    {
        $organizer = Organizer::first() ?? Organizer::create([
            'name' => 'Org', 'email' => 'org@example.com', 'password' => bcrypt('password'), 'approved' => true,
        ]);

        $event = Event::create(['title' => $title, 'status' => $status, 'organizer_id' => $organizer->id]);
        $event->venue()->create(['name' => 'Gedung Serbaguna']);
        $subevent = $event->subevents()->create(['title' => 'Sesi utama', 'start_at' => $startAt, 'end_at' => $startAt->copy()->addHours(3)]);
        $subevent->tickets()->create(['price' => $price, 'currency' => 'IDR']);

        return $event;
    }

    public function test_shows_empty_state_when_nothing_is_published()
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Belum ada event yang dibuka.');
    }

    public function test_lists_only_upcoming_published_events()
    {
        $this->makeEvent('Bazar Published', 'published', now()->addWeek(), 35000);
        $this->makeEvent('Konser Draft', 'draft', now()->addWeek());
        $this->makeEvent('Festival Lewat', 'published', now()->subWeek());

        $this->get('/')
            ->assertOk()
            ->assertSee('Bazar Published')
            ->assertSee('Rp35.000')
            ->assertDontSee('Konser Draft')
            ->assertDontSee('Festival Lewat')
            ->assertDontSee('Belum ada event yang dibuka.');
    }

    public function test_shows_error_state_when_events_cannot_be_loaded()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\Schema::drop('sub_events');

        $this->get('/')
            ->assertOk()
            ->assertSee('Daftar event gagal dimuat.');
    }
}
