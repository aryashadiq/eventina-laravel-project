@extends('layouts.app')

@section('title','Organizer Profile')

@section('content')
    <h3>Your profile</h3>
    <div class="container-card">
        <form method="POST" action="{{ route('organizers.profile.update') }}">@csrf
            <label class="small">Name</label>
            <input name="name" value="{{ $organizer->name }}">

            <label class="small">New password (leave blank to keep)</label>
            <input type="password" name="password">

            <label class="small">Confirm</label>
            <input type="password" name="password_confirmation">

            <button class="btn" style="margin-top:10px">Save</button>
        </form>
    </div>
@endsection
