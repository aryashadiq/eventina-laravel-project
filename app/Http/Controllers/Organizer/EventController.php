<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('organizer.events.index');
    }

    public function create()
    {
        return redirect()->route('organizers.events.wizard.start');
    }

    public function store(Request $request)
    {
        // store final event
    }

    // other resource methods omitted for brevity
}
