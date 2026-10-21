<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class OrganizerPasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('organizer.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::broker('organizers')->sendResetLink($request->only('email'));
        return back()->with('status', __($status));
    }
}
