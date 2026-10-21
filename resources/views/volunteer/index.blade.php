@extends('layouts.app')

@section('title', $event->title . ' - Volunteers')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Volunteer Applications</h1>
        <h2 class="text-xl font-semibold text-gray-600">{{ $event->title }}</h2>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if($volunteers->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600 text-lg">No volunteer applications yet.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Phone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Availability</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($volunteers as $volunteer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $volunteer->name }}</td>
                            <td class="px-6 py-4">{{ $volunteer->email }}</td>
                            <td class="px-6 py-4">{{ $volunteer->phone }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm">{{ ucfirst(str_replace('-', ' ', $volunteer->availability)) }}</span>
                            </td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if($volunteer->status === 'pending')
                                        <form action="{{ route('volunteer.approve', $volunteer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('volunteer.reject', $volunteer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="text-sm bg-gray-300 text-gray-600 px-3 py-1 rounded cursor-not-allowed">
                                            {{ ucfirst($volunteer->status) }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $volunteers->links() }}
        </div>
    @endif
</div>
@endsection
