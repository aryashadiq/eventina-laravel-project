<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\SubEvent;
use App\Filament\Resources\Events\EventResource;

class EventController extends Controller
{
    public function view(SubEvent $subevent)
    {
        // Load relations we'll need for the public view
        $subevent->loadMissing(['event.venue', 'tickets']);

        $event = $subevent->event;
        $venue = $event->venue ?? null;
        $support = $event->support ?? null;

        // Choose a ticket to display — adjust logic if you need a specific ticket
        $ticket = $subevent->tickets->first();

        // Compute hasPaid if user logged in and payments exist (placeholder)
        $hasPaid = false;

        return view('event.view', compact('event', 'subevent', 'venue', 'ticket', 'support', 'hasPaid'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->with('subevents')->firstOrFail();
        return view('events.show', ['event' => $event]);
    }

    public function index()
    {
        $model = EventResource::model();
        $events = $model::withCount('subevents')->orderBy('created_at','desc')->paginate(12);
        return view('events.index', ['events' => $events]);
    }
}
