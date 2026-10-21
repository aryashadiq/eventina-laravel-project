@extends('layouts.app')

@section('title', 'Register as Volunteer - ' . $event->title)

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-center mb-2">Volunteer Registration</h2>
        <p class="text-center text-gray-600 mb-8">{{ $event->title }}</p>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('volunteer.store', $event->id) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email ?? '') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Volunteer Experience</label>
                <textarea id="experience" name="experience" rows="3" placeholder="Tell us about your previous volunteer experience..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('experience') }}</textarea>
            </div>

            <div>
                <label for="motivation" class="block text-sm font-medium text-gray-700 mb-1">Why do you want to volunteer?</label>
                <textarea id="motivation" name="motivation" rows="3" placeholder="Share your motivation..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('motivation') }}</textarea>
            </div>

            <div>
                <label for="availability" class="block text-sm font-medium text-gray-700 mb-1">Availability *</label>
                <select id="availability" name="availability" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select availability</option>
                    <option value="full-day" {{ old('availability') === 'full-day' ? 'selected' : '' }}>Full Day</option>
                    <option value="morning" {{ old('availability') === 'morning' ? 'selected' : '' }}>Morning Only</option>
                    <option value="afternoon" {{ old('availability') === 'afternoon' ? 'selected' : '' }}>Afternoon Only</option>
                    <option value="evening" {{ old('availability') === 'evening' ? 'selected' : '' }}>Evening Only</option>
                    <option value="flexible" {{ old('availability') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Register as Volunteer
            </button>

            <a href="{{ route('events.show', $event->slug) }}" class="block text-center text-sm text-gray-600 hover:text-gray-900">
                Back to Event
            </a>
        </form>
    </div>
</div>
@endsection
