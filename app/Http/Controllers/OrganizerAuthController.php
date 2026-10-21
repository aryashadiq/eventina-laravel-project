<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('organizer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->guard('organizer')->attempt($credentials)) {
            $org = auth()->guard('organizer')->user();
            if (! $org->approved) {
                auth()->guard('organizer')->logout();
                return back()->withErrors(['email' => 'Account pending approval']);
            }
            return redirect()->route('organizers.dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
