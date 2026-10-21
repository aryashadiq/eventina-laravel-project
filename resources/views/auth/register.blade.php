@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <x-AuthCard>
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div>
                <x-InputLabel for="name" :value="__('Name')" />
                <x-TextInput id="name" type="text" name="name" value="{{ old('name') }}"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none" autofocus />
                <x-InputError :message="$errors->first('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-InputLabel for="email" :value="__('Email')" />
                <x-TextInput id="email" type="email" name="email" value="{{ old('email') }}"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none" autocomplete="username" />
                <x-InputError :message="$errors->first('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-InputLabel for="password" :value="__('Password')" />
                <x-TextInput id="password" type="password" name="password"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none"
                    autocomplete="new-password" />
                <x-InputError :message="$errors->first('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-InputLabel for="password_confirmation" :value="__('Confirm Password')" />
                <x-TextInput id="password_confirmation" type="password" name="password_confirmation"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none"
                    autocomplete="new-password" />
                <x-InputError :message="$errors->first('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('login') }}"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Already have an account?') }}
                </a>

                <x-PrimaryButton class="ml-4">{{ __('Register') }}</x-PrimaryButton>
            </div>
        </form>
    </x-AuthCard>
@endsection
