@extends('layouts.app')

@section('title', 'Buy Ticket')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ optional($event)->title ?? 'Event' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($subevent)->title ?? 'Session' }}</p>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Ticket code</p>
                        <div class="text-lg font-medium">{{ $ticket->code ?? '—' }}</div>
                    </div>
                </div>

                <div class="md:col-span-1 flex flex-col justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Price</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $ticket->currency ?? 'IDR' }} {{ number_format($ticket->price ?? 0, 2) }}</div>
                    </div>

                    <form method="POST" action="{{ route('tickets.buy', ['ticket' => $ticket->id]) }}">
                        @csrf
                        <div class="mt-4">
                            <span class="block text-sm font-medium text-gray-700">Payment method</span>
                            <p class="mt-1 text-sm text-gray-900">QRIS</p>
                            <input type="hidden" name="method" value="qris">
                        </div>

                        <button type="submit" class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Pay now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection