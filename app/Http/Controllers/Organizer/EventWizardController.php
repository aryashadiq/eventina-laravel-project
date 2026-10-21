<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\SubEvent;
use App\Models\Ticket;
use App\Models\Venue;
use App\Models\Support;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventWizardController extends Controller
{
    protected $steps = ['general', 'subevents', 'tickets', 'support'];

    public function start()
    {
        session(['event_wizard' => []]);
        return redirect()->route('organizers.events.wizard.show', ['step' => 'general']);
    }

    public function showStep($step)
    {
        if (! in_array($step, $this->steps)) {
            abort(404);
        }

        $data = session('event_wizard', []);
        return view('organizer.events.wizard.' . $step, ['data' => $data]);
    }

    public function postStep(Request $request, $step)
    {
        if (! in_array($step, $this->steps)) {
            abort(404);
        }

        $data = session('event_wizard', []);
        $data[$step] = $request->all();
        session(['event_wizard' => $data]);

        $next = $this->getNextStep($step);
        if ($next) {
            return redirect()->route('organizers.events.wizard.show', ['step' => $next]);
        }

        // Finalize: create Event + related models
        $saved = DB::transaction(function () use ($data) {
            $organizerId = Auth::guard('organizer')->id() ?: (Auth::id() ?: null);
            // Fallback: if not authenticated as organizer, attempt to find a seeded organizer
            if (! $organizerId) {
                $organizer = \App\Models\Organizer::first();
                $organizerId = $organizer ? $organizer->id : null;
            }

            $general = $data['general'] ?? [];
            $event = Event::create([
                'title' => $general['title'] ?? 'Untitled Event',
                'description' => $general['description'] ?? null,
                'organizer_id' => $organizerId,
                'status' => 'draft',
            ]);

            // venue - optional
            if (! empty($general['venue_name']) || ! empty($general['venue_address'])) {
                Venue::create([
                    'event_id' => $event->id,
                    'name' => $general['venue_name'] ?? null,
                    'address' => $general['venue_address'] ?? null,
                ]);
            }

            // subevents
            $subevents = $data['subevents']['subevents'] ?? $data['subevents'] ?? [];
            $createdSubEvents = [];
            if (is_array($subevents)) {
                foreach ($subevents as $se) {
                    $created = SubEvent::create([
                        'event_id' => $event->id,
                        'title' => $se['title'] ?? null,
                        'start_at' => $se['start_at'] ?? null,
                        'end_at' => $se['end_at'] ?? null,
                    ]);
                    $createdSubEvents[] = $created;
                }
            }

            // tickets - associate to first subevent if any
            $tickets = $data['tickets']['tickets'] ?? $data['tickets'] ?? [];
            $subIndex = $data['tickets']['subevent_index'] ?? null;
            if (is_numeric($subIndex)) {
                $subIndex = (int) $subIndex;
            } else {
                $subIndex = null;
            }

            if (is_array($tickets) && count($tickets) > 0) {
                $targetSub = $createdSubEvents[$subIndex] ?? $createdSubEvents[0] ?? null;
                foreach ($tickets as $t) {
                    Ticket::create([
                        'sub_event_id' => $targetSub ? $targetSub->id : null,
                        'code' => $t['code'] ?? null,
                        'currency' => $t['currency'] ?? ($t['price_currency'] ?? 'IDR'),
                        'price' => $t['price'] ?? ($t['amount'] ?? 0),
                        'tax' => $t['tax'] ?? 0,
                    ]);
                }
            }

            // support
            $support = $data['support'] ?? [];
            if (! empty($support)) {
                Support::create([
                    'event_id' => $event->id,
                    'contact_name' => $support['contact_name'] ?? null,
                    'contact_email' => $support['contact_email'] ?? null,
                    'contact_phone' => $support['contact_phone'] ?? null,
                ]);
            }

            return $event;
        });

        session()->forget('event_wizard');
        return redirect()->route('organizers.dashboard')->with('status', 'Event created');
    }

    public function discard(Request $request)
    {
        session()->forget('event_wizard');
        return redirect()->route('organizers.dashboard')->with('status', 'Event discarded');
    }

    protected function getNextStep($current)
    {
        $i = array_search($current, $this->steps);
        return isset($this->steps[$i + 1]) ? $this->steps[$i + 1] : null;
    }
}
