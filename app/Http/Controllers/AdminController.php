<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function eventsJson()
    {
        $query = Event::withCount('subevents')->select('events.*');
        return DataTables::of($query)->toJson();
    }

    public function ticketsJson()
    {
        $query = Ticket::with('subevent')->select('tickets.*');
        return DataTables::of($query)->toJson();
    }

    public function paymentsJson()
    {
        $query = Payment::with('invoice')->select('payments.*');
        return DataTables::of($query)->toJson();
    }

    // Admin pages
    public function index()
    {
        return view('admin.dashboard');
    }

    public function eventsPage()
    {
        return view('admin.events');
    }

    public function ticketsPage()
    {
        return view('admin.tickets');
    }

    public function paymentsPage()
    {
        return view('admin.payments');
    }

    // Organizer approvals
    public function pendingOrganizers()
    {
        $filter = request('filter', 'pending');
        $q = \App\Models\Organizer::query();
        if ($filter === 'pending') $q->where('approved', false);
        if ($filter === 'approved') $q->where('approved', true);
        $organizers = $q->get();
        return view('admin.organizers_pending', ['organizers' => $organizers, 'filter' => $filter]);
    }

    public function approveOrganizer($id)
    {
        $org = \App\Models\Organizer::findOrFail($id);
        $org->approved = true;
        $org->save();
        try {
            \Mail::to($org->email)->send(new \App\Mail\OrganizerApproved($org));
        } catch (\Throwable $e) {}
        return redirect()->route('backend.organizers.pending')->with('status','Organizer approved');
    }

    public function revokeOrganizer($id)
    {
        $org = \App\Models\Organizer::findOrFail($id);
        $org->approved = false;
        $org->save();
        return redirect()->route('backend.organizers.pending')->with('status','Organizer approval revoked');
    }
}
