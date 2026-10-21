@extends('layouts.app')

@section('title','Organizer Register')

@section('content')
    <div style="max-width:620px;margin:0 auto">
        <div class="container-card">
            <h3>Create Organizer Account</h3>
            <form method="POST" action="{{ route('organizers.register.post') }}">@csrf
                <label class="small">Name</label>
                <input name="name" value="{{ old('name') }}">

                <label class="small">Email</label>
                <input name="email" value="{{ old('email') }}">

                <label class="small">Password</label>
                <input name="password" type="password">

                <label class="small">Confirm</label>
                <input name="password_confirmation" type="password">

                <button class="btn" style="margin-top:10px">Register</button>
            </form>
        </div>
    </div>
@endsection
