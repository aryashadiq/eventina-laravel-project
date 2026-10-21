@extends('layouts.app')

@section('title', 'My Volunteer Applications')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Volunteer Applications</h1>

    @if($volunteers->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-6xl mb-4">🤝</div>
            <p class="text-gray-600 text-lg mb-4">You haven't registered as a volunteer for any events yet.</p>
            <a href="{{ route('events.index') }}" class="inline-block bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700">
                Browse Events
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($volunteers as $volunteer)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                        <h3 class="text-xl font-bold">{{ $volunteer->event->title }}</h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($volunteer->status === 'approved')
                                    bg-green-100 text-green-800
                                @elseif($volunteer->status === 'rejected')
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($volunteer->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm">
                            <p><span class="font-semibold">Name:</span> {{ $volunteer->name }}</p>
                            <p><span class="font-semibold">Email:</span> {{ $volunteer->email }}</p>
                            <p><span class="font-semibold">Phone:</span> {{ $volunteer->phone }}</p>
                            <p><span class="font-semibold">Availability:</span> {{ ucfirst(str_replace('-', ' ', $volunteer->availability)) }}</p>
                        </div>

                        @if($volunteer->motivation)
                            <div class="mt-4 p-3 bg-gray-50 rounded">
                                <p class="text-sm"><span class="font-semibold">Why:</span> {{ Str::limit($volunteer->motivation, 100) }}</p>
                            </div>
                        @endif

                        <div class="mt-4 text-xs text-gray-500">
                            Registered: {{ $volunteer->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
