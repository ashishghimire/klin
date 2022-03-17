<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">
    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('#bill-info').DataTable({
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 'no-sort'
                    },
                        {
                            "searchable": false,
                            "targets": 'no-search'
                        }],
                    "order": [[6, 'desc']],
                });
            });
        </script>
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bills
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif


    <table id="bill-info" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Estimate No.</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Amount</th>
            <th>Payment Status</th>
            <th>Payment Mode</th>
            <th>Date</th>
            <th class="no-sort">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($bills as $bill)
            <tr>
                <td>{{$bill->estimate_no}}</td>
                <td>{{$bill->customer->name}}</td>
                <td>{{$bill->customer->phone}}</td>
                <td>{{$bill->amount}}</td>
                <td>
                    @if($bill->payment_status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @elseif($bill->payment_status == 'partial')
                        <span class="badge bg-warning text-dark">Partially Paid</span>
                    @else
                        <span class="badge bg-danger">Unpaid</span>
                    @endif
                </td>
                <td>{{!empty($bill->payment_mode) ? $bill->payment_mode : '-'}}</td>
                <td>{{!empty($bill->created_at) ? $bill->created_at : '-'}}</td>
                <td><a class="btn btn-outline-dark" href="{{route('bill.edit',  $bill->id)}}">Edit </a></td>
                {{--<td><a class="btn btn-outline-dark" href="{{route('customer.edit', $customer->id)}}">Edit </a>--}}
                    {{--<a class="btn btn-outline-dark" href="{{route('customer.bill.create', $customer->id)}}">Create--}}
                        {{--Bill </a></td>--}}
            </tr>
        @empty
            <tr>No Customers Found</tr>
        @endforelse
    </table>

</x-app-layout>
