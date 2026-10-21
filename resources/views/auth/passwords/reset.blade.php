@extends('layouts.app')

@section('title','Reset Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3>Reset password</h3>
                <form method="POST" action="{{ route('password.update') }}">@csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3"><label>Email</label><input class="form-control" name="email" value="{{ $email ?? old('email') }}"></div>
                    <div class="mb-3"><label>New password</label><input class="form-control" name="password" type="password"></div>
                    <div class="mb-3"><label>Confirm</label><input class="form-control" name="password_confirmation" type="password"></div>
                    <button class="btn btn-primary">Reset password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
