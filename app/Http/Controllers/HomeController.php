<?php

namespace App\Http\Controllers;

use App\Models\SubEvent;

class HomeController extends Controller
{
    public function index()
    {
        $upcoming = collect();
        $loadFailed = false;

        try {
            $upcoming = SubEvent::query()
                ->with(['event.venue', 'tickets'])
                ->whereHas('event', fn ($query) => $query->where('status', 'published'))
                ->where('start_at', '>=', now())
                ->orderBy('start_at')
                ->limit(7)
                ->get();
        } catch (\Throwable $e) {
            report($e);
            $loadFailed = true;
        }

        return view('home', [
            'featured' => $upcoming->first(),
            'upcoming' => $upcoming->slice(1),
            'loadFailed' => $loadFailed,
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
