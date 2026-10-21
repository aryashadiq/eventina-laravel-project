<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organizer;
use Spatie\Permission\Models\Role;

class OrganizerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('organizer.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $org = Organizer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'approved' => false,
        ]);

        // assign role
        $role = Role::firstOrCreate(['name'=>'organizer','guard_name'=>'organizer']);
        $org->assignRole($role);

        // notify admins
        try {
            \Mail::to(config('mail.from.address'))->send(new \App\Mail\OrganizerRegistered($org));
        } catch (\Throwable $e) {
            // swallow in dev
        }

        return redirect()->route('organizers.login')->with('status','Account created. Awaiting admin approval.');
    }

    public function profile()
    {
        $org = auth()->guard('organizer')->user();
        return view('organizer.profile', ['organizer' => $org]);
    }

    public function updateProfile(Request $request)
    {
        $org = auth()->guard('organizer')->user();
        $data = $request->validate(['name'=>'required','password'=>'nullable|confirmed|min:6']);
        $org->name = $data['name'];
        if (!empty($data['password'])) $org->password = bcrypt($data['password']);
        $org->save();
        return back()->with('status','Profile updated');
    }
}
