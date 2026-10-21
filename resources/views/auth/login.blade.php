@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <x-AuthCard>
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div>
                <x-InputLabel for="email" :value="__('Email')" />
                <x-TextInput id="email" type="email" name="email" value="{{ old('email') }}"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none" autocomplete="username"
                    autofocus />
                <x-InputError :message="$errors->first('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-InputLabel for="password" :value="__('Password')" />
                <x-TextInput id="password" type="password" name="password"
                    class="mt-1 input input-bordered input-primary w-full focus:outline-none"
                    autocomplete="current-password" />
                <x-InputError :message="$errors->first('password')" class="mt-2" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">

                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}
                        class="inline-block align-middle w-4 h-4"
                        style="display:inline-block !important; width:auto !important; height:auto !important; vertical-align:middle;" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-PrimaryButton class="ml-4">{{ __('Log in') }}</x-PrimaryButton>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('register') }}"
                    class="ml-2 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Register here') }}
                </a>
            </div>
        </form>
    </x-AuthCard>
@endsection
