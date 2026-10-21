<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    public function register(Event $event)
    {
        return view('volunteer.register', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'experience' => 'nullable|string|max:1000',
            'motivation' => 'nullable|string|max:1000',
            'availability' => 'required|string|in:full-day,morning,afternoon,evening,flexible',
        ]);

        // Check if already registered
        $existing = Volunteer::where('event_id', $event->id)
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            return redirect()->route('volunteer.register', $event->id)
                ->with('error', 'You have already registered as a volunteer for this event.');
        }

        $volunteer = new Volunteer($validated);
        $volunteer->event_id = $event->id;
        if (auth()->check()) {
            $volunteer->user_id = auth()->id();
        }
        $volunteer->save();

        return redirect()->route('events.show', $event->slug)
            ->with('status', 'Thank you for registering as a volunteer! We will review your application and contact you soon.');
    }

    public function myVolunteers()
    {
        $volunteers = auth()->user()->volunteers ?? [];
        return view('volunteer.my-volunteers', compact('volunteers'));
    }

    public function index(Event $event)
    {
        $this->authorize('view', $event);
        $volunteers = $event->volunteers()->paginate(15);
        return view('volunteer.index', compact('event', 'volunteers'));
    }

    public function show(Volunteer $volunteer)
    {
        $this->authorize('view', $volunteer);
        return view('volunteer.show', compact('volunteer'));
    }

    public function approve(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);
        $volunteer->update(['status' => 'approved']);
        return redirect()->back()->with('status', 'Volunteer approved successfully.');
    }

    public function reject(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);
        $volunteer->update(['status' => 'rejected']);
        return redirect()->back()->with('status', 'Volunteer rejected.');
    }
}
