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

                $(document).on('change', '.payment-status', function () {
                    var billId = $(this).data('bill');
                    var paymentStatus = $(this).val();
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        method: "POST",
                        url: "{{url('change-payment-status')}}/" + billId,

                        // paymentStatus: $(this).val(),
                        // billId: billId,
                        data: {payment_status: paymentStatus},
                        success: function (data) {
                            console.log(data);
                        },
                        error: function (data) {
                            alert("fail");
                        }
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
            <th class="no-sort">Payment Status</th>
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
                    {{--<select name="payment_status" class="payment-status" data-bill="{{$bill->id}}">--}}
                    {{--<option value="paid" {{$bill->payment_status == 'paid' ? 'selected' : ''}}>Paid</option>--}}
                    {{--<option value="partial" {{$bill->payment_status == 'partial' ? 'selected' : ''}}>Partially--}}
                    {{--Paid--}}
                    {{--</option>--}}
                    {{--<option value="unpaid" {{$bill->payment_status == 'unpaid' ? 'selected' : ''}}>Unpaid</option>--}}
                    {{--</select>--}}
                    @if($bill->payment_status == 'paid')
                        <button type="button" class="btn btn-info btn-sm"
                                disabled="disabled">{{$bill->payment_status}}</button>
                    @else
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-{{$bill->id}}">{{$bill->payment_status}}</button>
                    @endif
                </td>
                <td>{{!empty($bill->payment_mode) ? $bill->payment_mode : '-'}}</td>
                <td>{{!empty($bill->created_at) ? $bill->created_at : '-'}}</td>
                <td><a class="btn btn-outline-dark" href="{{route('bill.edit',  $bill->id)}}">Edit </a></td>
            </tr>
            <div class="modal fade" id="modal-{{$bill->id}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-5"> Estimate no. {{$bill->estimate_no}}</div>

                                    <div class="col-md-5 ms-auto">
                                        <small>{{!empty($bill->created_at) ? date('d-m-Y', strtotime($bill->created_at)) : ''}}</small>
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
