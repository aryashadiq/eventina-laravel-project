@extends('layouts.app')

@section('title','Admin - Events')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h2 style="margin:0">Events</h2>
        <a href="#" class="btn">New event</a>
    </div>
    <div class="card p-3">
        <table id="events-table" class="table w-100">
            <thead><tr><th>ID</th><th>Title</th><th>Subevents</th><th>Created</th></tr></thead>
        </table>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(function(){
            $('#events-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("admin.json.events") !!}',
                columns:[
                    {data:'id', name:'id'},
                    {data:'title', name:'title'},
                    {data:'subevents_count', name:'subevents_count', orderable:false, searchable:false},
                    {data:'created_at', name:'created_at'}
                ]
            });
        });
    </script>
@endsection
