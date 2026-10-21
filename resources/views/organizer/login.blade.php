@extends('layouts.app')

@section('title','Organizer Login')

@section('content')
    <div style="max-width:520px;margin:0 auto">
        <div class="container-card">
            <h3>Organizer Sign in</h3>

            @if($errors->any())
                <div class="flash">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('organizers.login.post') }}">@csrf
                <label class="small">Email</label>
                <input name="email" placeholder="email" value="{{ old('email') }}">

                <label class="small">Password</label>
                <input name="password" placeholder="password" type="password">

                <button class="btn" type="submit" style="width:100%;margin-top:6px">Login</button>
            </form>
        </div>
    </div>
@endsection