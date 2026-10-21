@extends('layouts.app')

@section('title','Pending Organizers')

@section('content')
    <h2>Organizers</h2>
    <div class="mb-3">
        <a class="btn btn-outline-primary btn-sm" href="{{ route('backend.organizers.pending', ['filter' => 'pending']) }}">Pending</a>
        <a class="btn btn-outline-primary btn-sm" href="{{ route('backend.organizers.pending', ['filter' => 'approved']) }}">Approved</a>
        <a class="btn btn-outline-primary btn-sm" href="{{ route('backend.organizers.pending', ['filter' => 'all']) }}">All</a>
    </div>
    <div class="card p-3">
        <table class="table">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($organizers as $o)
                    <tr>
                        <td>{{ $o->id }}</td>
                        <td>{{ $o->name }}</td>
                        <td>{{ $o->email }}</td>
                        <td>
                            @if(!$o->approved)
                                <form method="POST" action="{{ route('backend.organizers.approve', ['id' => $o->id]) }}" style="display:inline">@csrf
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('backend.organizers.revoke', ['id' => $o->id]) }}" style="display:inline">@csrf
                                    <button class="btn btn-sm btn-warning">Revoke</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
