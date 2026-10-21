@extends('layouts.app')

@section('title','Password Reset')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3>Reset password</h3>
                @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                <form method="POST" action="{{ route('password.email') }}">@csrf
                    <div class="mb-3"><label>Email</label><input class="form-control" name="email"></div>
                    <button class="btn btn-primary">Send reset link</button>
                </form>
            </div>
        </div>
    </div>
@endsection
