@extends('layouts.app')

@section('title','Admin - Payments')

@section('content')
    <h2>Payments</h2>
    <div class="card p-3">
        <table id="payments-table" class="table table-striped w-100">
            <thead><tr><th>ID</th><th>Invoice</th><th>Amount</th><th>Status</th><th>Created</th></tr></thead>
        </table>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(function(){
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("admin.json.payments") !!}',
                columns:[
                    {data:'id', name:'id'},
                    {data:'invoice_id', name:'invoice_id'},
                    {data:'amount', name:'amount'},
                    {data:'status', name:'status'},
                    {data:'created_at', name:'created_at'}
                ]
            });
        });
    </script>
@endsection
