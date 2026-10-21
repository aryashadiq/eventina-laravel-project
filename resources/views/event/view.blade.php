@extends('layouts.app')

@section('title', 'Event')

@section('content')
    @php
        // Ensure template variables are defined to avoid Undefined variable errors when controller
        // doesn't pass all of them. Controllers should ideally pass these, but this makes view robust.
        $subevent = $subevent ?? ($subevent = isset($subevent) ? $subevent : null);
        $venue = $venue ?? null;
        $ticket = $ticket ?? null;
        $event = $event ?? null;
        $support = $support ?? null;
        $hasPaid = $hasPaid ?? false;
    @endphp
    {{-- Flash message (compatible with session flash structure used by Inertia or manual flashes) --}}
    @if(session('flash.message'))
        <div class="mx-auto my-4 w-2/3">
            <div class="px-4 py-3 rounded shadow @if(session('flash.message.type') === 'success') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                {{ session('flash.message.message') }}
            </div>
        </div>
    @elseif(session('message'))
        <div class="mx-auto my-4 w-2/3">
            <div class="px-4 py-3 rounded shadow bg-green-100 text-green-800">
                {{ session('message') }}
            </div>
        </div>
    @endif

    {{-- Hero image --}}
    <div class="w-full">
        <img src="/img/banner.jpg" alt="banner" class="w-full h-48 object-cover">
    </div>

    <div class="flex items-center justify-center my-4">
        <div class="flex flex-col w-2/3">
            <div>
                <h1 class="text-4xl font-bold mt-8">
                    {{ optional($subevent)->title ?? optional($event)->title ?? 'Untitled' }}
                </h1>

                @php
                    $displayDate = '';
                    $startAt = optional($subevent)->start_at ?? optional($subevent)->event_date ?? null;
                    if ($startAt) {
                        try {
                            $displayDate = \Carbon\Carbon::parse($startAt)->translatedFormat('j M Y H:i');
                        } catch (\Throwable $e) {
                            $displayDate = $startAt;
                        }
                    }
                @endphp

                <p class="font-bold text-md">{{ $displayDate }}</p>
            </div>

            <div class="my-4">
                <p>{{ optional($venue)->address }}</p>
                <p>
                    {{ optional($venue)->city }}@if(optional($venue)->city && (optional($venue)->state || optional($venue)->country)), @endif {{ optional($venue)->state }}@if(optional($venue)->state && optional($venue)->country), @endif {{ optional($venue)->country }}
                </p>
            </div>

            <p class="text-2xl font-bold">
                @php
                    $price = optional($ticket)->price;
                    $currency = optional($ticket)->currency ?? '';
                    $priceFormatted = is_numeric($price) ? number_format((float)$price, 2, '.', ',') : ($price ?? '0.00');
                @endphp
                {{ $currency }} {{ $priceFormatted }}
            </p>

            <div class="card-actions justify-end mt-4">
                @php
                    $disabled = !empty($hasPaid);
                @endphp

                @if(optional($ticket)->id && !$disabled)
                    <a href="{{ route('tickets.buy.show', ['ticket' => $ticket->id]) }}"
                       class="btn btn-primary inline-block px-4 py-2 rounded text-white">
                        Buy Ticket
                    </a>
                @else
                    <button type="button" disabled class="btn btn-primary inline-block px-4 py-2 rounded text-white opacity-50 pointer-events-none">
                        Buy Ticket
                    </button>
                @endif

                <a href="{{ route('volunteer.register', ['event' => $event->id]) }}"
                   class="btn btn-secondary inline-block px-4 py-2 rounded text-white bg-green-600 hover:bg-green-700 ml-2">
                    Become a Volunteer
                </a>
            </div>
        </div>
    </div>

    <div class="flex justify-center bg-gray-100 py-4">
        <div class="w-2/3">
            <p class="text-lg my-4">{{ optional($event)->description }}</p>
            <p class="text-lg font-bold">
                Below are the included services you get if you purchase the ticket:
            </p>
            <ul class="list-disc">
                <div class="grid grid-cols-2 ml-8">
                    <li>
                        <div class="col-span-1">
                            email1@example.com
                        </div>
                    </li>
                </div>
            </ul>

            <p class="text-lg font-bold my-4">Support contact:</p>
            <p>Address: {{ optional($support)->address }}</p>
            <p>Email: {{ optional($support)->email }}</p>
            <p>Phone: {{ optional($support)->phone }}</p>
            <p>Mobile: {{ optional($support)->mobile }}</p>
        </div>
    </div>
@endsection