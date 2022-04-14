<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">
    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            $(document).ready(function () {

                $('#customer-info').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('customer.index') }}",
                    "order": [[3, 'desc']],
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'address', name: 'address'},
                        {data: 'phone', name: 'phone'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'reward_points', name: 'reward_points'},
                        {data: 'billing', name: 'billing', orderable: false, searchable: false},
                        {data: 'edit', name: 'edit', orderable: false, searchable: false},
                    ]
                });

            });
        </script>
    @stop
    <x-slot name="header">
        <a href="{{route('customer-export')}}">
            <small>Download All Data</small>
        </a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All customers') }}
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif


    <table id="customer-info" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Joined Date</th>
            <th>Reward Points</th>
            <th>Billing</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
    </table>

</x-app-layout>
