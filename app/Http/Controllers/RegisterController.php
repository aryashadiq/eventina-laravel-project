<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Ensure default 'user' role exists and assign to the new account
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->assignRole('user');

        // Login using the web guard and redirect to the user dashboard
        Auth::guard('web')->login($user);

        return redirect()->route('dashboard')->with('status', 'Account created successfully!');
    }
}
