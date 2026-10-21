<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $invoices = $user->invoices()
            ->with(['ticket.subevent.event', 'payment'])
            ->latest()
            ->get();
        
        $paid_count = $invoices->filter(fn ($invoice) => $invoice->payment && $invoice->payment->paid)->count();
        $pending_count = $invoices->count() - $paid_count;
        
        return view('user.dashboard', [
            'invoices' => $invoices,
            'user' => $user,
            'paid_count' => $paid_count,
            'pending_count' => $pending_count
        ]);
    }
}
