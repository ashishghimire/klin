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

                $(document).on('change', '.laundry-status', function () {
                    $(this).attr('disabled', 'disabled');
                    var billId = $(this).data('bill');
                    var laundryStatus = $(this).val();
                    var self = $(this);
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        method: "POST",
                        url: "{{url('change-laundry-status')}}/" + billId,

                        data: {laundry_status: laundryStatus},
                        success: function (data) {
                            self.removeAttr('disabled');
                        },
                    });
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
            <th class="no-sort">Laundry Status</th>
            <th>Date</th>
            @if(auth()->user()->role == 'admin')
                <th class="no-sort">Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($bills as $bill)
            <tr>
                <td>
                    <a href="{{route('customer.bill.show', [$bill->customer->id, $bill->id])}}"> {{$bill->estimate_no}}</a>
                </td>
                <td><a href="{{route('customer.show', [$bill->customer->id])}}">{{$bill->customer->name}}</a></td>
                <td><a href="{{route('customer.show', [$bill->customer->id])}}">{{$bill->customer->phone}}</a></td>
                <td>{{$bill->amount}}</td>
                <td>
                    @if($bill->payment_status == 'paid')
                        <button type="button" class="btn btn-info btn-sm"
                                disabled="disabled">{{$bill->payment_status}}</button>
                    @else
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-{{$bill->id}}">{{$bill->payment_status}}</button>
                    @endif
                </td>
                <td>
                    <?php
                    if ($bill->payment_status == 'paid') {
                        $laundryStatusArray = ['unprocessed' => 'Unprocessed', 'processing' => 'Processing', 'completed' => 'Completed', 'delivered' => 'Delivered'];
                    } else {
                        $laundryStatusArray = ['unprocessed' => 'Unprocessed', 'processing' => 'Processing', 'completed' => 'Completed'];
                    }
                    ?>
                    {!! Form::select('laundry_status',$laundryStatusArray, $bill->laundry_status, ['class'=>'laundry-status', 'data-bill'=>$bill->id]) !!}
                </td>
                <td>{{!empty($bill->nepali_date) ? $bill->nepali_date : ''}}</td>
                @if(auth()->user()->role == 'admin')
                    {{--@if($bill->payment_status != 'paid')--}}
                        <td><a class="btn btn-outline-dark" href="{{route('bill.edit',  $bill->id)}}">Edit </a></td>
                    {{--@endif--}}
                @endif
            </tr>
            <div class="modal fade" id="modal-{{$bill->id}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-5"> Estimate no. {{$bill->estimate_no}}</div>

                                    <div class="col-md-5 ms-auto">
                                        <small>{{!empty($bill->nepali_date) ? $bill->nepali_date : ''}}</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="align-self-md-auto">Customer: {{$bill->customer->name}}</div>

                                </div>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    Amount: {{$bill->amount}}
                                </div>
                                {!! Form::open(['route'=>['change-payment-status', $bill->id]]) !!}
                                <div class="row">
                                    {!! Form::select('payment_mode',  $paymentModes->pluck('name', 'name') , $bill->payment_mode, ['placeholder' => 'Not paid', 'class' => 'form-select form-select-sm payment']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-outline-primary">Pay</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @empty
            <tr>No Customers Found</tr>
        @endforelse
        </tbody>
    </table>

</x-app-layout>
