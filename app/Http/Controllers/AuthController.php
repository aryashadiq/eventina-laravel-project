<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If already authenticated as regular user, redirect to dashboard
        if (Auth::guard('web')->check()) {
            return redirect('/dashboard');
        }
        
        // If admin is logged in, they should use /admin
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');
        
        // Try regular user authentication ONLY (not admin)
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            // Extra safety check - if somehow an admin got through, redirect them
            $user = Auth::guard('web')->user();
            if ($user->hasRole('admin')) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Admin harus login via admin panel'])->withInput();
            }
            return redirect()->intended('/dashboard');
        }
        
        // Try organizer guard
        if (Auth::guard('organizer')->attempt($credentials, $request->filled('remember'))) {
            $org = Auth::guard('organizer')->user();
            if (! $org->approved) {
                Auth::guard('organizer')->logout();
                return back()->withErrors(['email' => 'Account pending approval'])->withInput();
            }
            return redirect()->intended(route('organizers.dashboard'));
        }
        
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        // Logout from whichever guard is currently authenticated
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('organizer')->check()) {
            Auth::guard('organizer')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
