@extends('layouts.app')

@section('title','Organizer Dashboard')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h2 style="margin:0">Organizer Dashboard</h2>
        <div>
            <a class="btn" href="{{ route('organizers.events.wizard.start') }}">Create Event</a>
            <form method="POST" action="{{ route('organizers.events.wizard.discard') }}" style="display:inline">@csrf<button class="btn ghost" style="margin-left:8px">Discard Wizard</button></form>
        </div>
    </div>

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <div class="container-card">
        <p class="muted">Welcome to your organizer dashboard. Use the "Create Event" button to start.</p>
    </div>
@endsection