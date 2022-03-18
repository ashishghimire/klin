<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">
    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            $(document).ready(function () {
                // $('#customer-info').DataTable({
                //     "columnDefs": [{
                //         "searchable": false,
                //         "orderable": false,
                //         "targets": 'no-sort'
                //     },
                //         {
                //             "searchable": false,
                //             "targets": 'no-search'
                //         }],
                //     "order": [[3, 'desc']],
                // });


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
                        {data: 'amount_spent', name: 'amount_spent'},
                        {data: 'reward_points', name: 'reward_points'},
                        {data: 'billing', name: 'billing', orderable: false, searchable: false},
                        {data: 'edit', name: 'edit', orderable: false, searchable: false},
                    ]
                });

            });
        </script>
    @stop
    <x-slot name="header">
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
            <th>Amount Spent</th>
            <th>Reward Points</th>
            <th>Billing</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        {{--@forelse($customers as $customer)--}}
            {{--<tr>--}}
                {{--<td>{{$customer->name}}</td>--}}
                {{--<td>{{$customer->address}}</td>--}}
                {{--<td>{{$customer->phone}}</td>--}}
                {{--<td>{{!empty($customer->created_at) ? $customer->created_at : '-'}}</td>--}}
                {{--<td>{{$customer->amount_spent}}</td>--}}
                {{--<td>{{$customer->reward_points}}</td>--}}
                {{--<td><a class="btn btn-outline-dark" href="{{route('customer.edit', $customer->id)}}">Edit </a>--}}
                    {{--<a class="btn btn-outline-dark" href="{{route('customer.bill.create', $customer->id)}}">Create Bill </a></td>--}}
            {{--</tr>--}}
        {{--@empty--}}
            {{--<tr>No Customers Found</tr>--}}
        {{--@endforelse--}}
    </table>

</x-app-layout>
