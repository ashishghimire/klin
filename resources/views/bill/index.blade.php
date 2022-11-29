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
                    "iDisplayLength": 100,
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 'no-sort',
                    },
                        {
                            "searchable": false,
                            "targets": 'no-search'
                        }],
                    "order": [[7, 'desc']],
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
            Invoices {{ !empty(request()->get('startDate')) && !empty(request()->get('endDate')) ?  'from '.request()->get('startDate').' to '.request()->get('endDate') :  '' }}
            <a class="btn-sm btn-outline-primary" href="{{route('invoice.create')}}">Add</a>
        </h2>
        <br>
        <form action={{route('bill.search')}} method="GET" role="search" class="search">
            {{ csrf_field() }}
            Get invoices for

            {!! Form::text('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15', 'required']) !!}

            {!! Form::text('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30', 'required']) !!}

            {!! Form::submit('Search by Date', ['class' => 'btn btn-outline-primary']); !!}
        </form>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <table id="bill-info" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Estimate No.</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Services</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Mode</th>
                    <th class="no-sort">Laundry Status</th>
                    <th>Date</th>
                    <th class="no-sort">Note</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="no-sort">Added By</th>
                    @endif
                    @if(auth()->user()->role == 'admin')
                        <th class="no-sort">Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @forelse($bills as $bill)
                    <tr>
                        <td style="width: 2%">
                            <a href="{{route('customer.bill.show', [$bill->customer->id, $bill->id])}}"> {{$bill->id}}</a>
                        </td>
                        <td><a href="{{route('customer.show', [$bill->customer->id])}}">{{$bill->customer->name}}</a>
                        </td>
                        <td style="width: 2%"><a
                                href="{{route('customer.show', [$bill->customer->id])}}">{{$bill->customer->phone}}</a>
                        </td>
                        <td>
                            <?php
                            $shortcodes = [];
                            foreach ($bill->service_details as $service_detail) {
                                $servicesShortcode = \App\Models\Service::where('name', $service_detail['service_type'])->first('shortcode');
                                if (!empty ($servicesShortcode)) {
                                    array_push($shortcodes, $servicesShortcode->shortcode);
                                }
                            }
                            ?>
                            {{implode($shortcodes, ', ')}}
                        </td>
                        <td>{{$bill->amount}}</td>
                        <td style="width: 2%">
                            <?php
                            $imagePath = asset('images/payment_modes/money.png');
                            if (!empty($bill->payment_mode)) {
                                if (file_exists(config('public.path') . '/images/payment_modes/' . $bill->payment_mode . '.png')) { //base_path() if in production
                                    $imagePath = asset('images/payment_modes/' . $bill->payment_mode . '.png');
                                }
                            } else {
                                $imagePath = asset('images/payment_modes/unpaid.png');
                            }
                            ?>
                            @if($bill->payment_status == 'paid')
                                <button type="button" class="btn btn-info btn-sm"
                                        disabled="disabled">{{$bill->payment_status}}</button>

                            @else
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal-{{$bill->id}}">{{$bill->payment_status}}</button>
                            @endif
                        </td>
                        <td>
                            @if (!empty($bill->payment_mode))
                                {{$bill->payment_mode}}
                                <img src="{{asset($imagePath)}}" style="width:20px;float:left; margin-right: 10%">
                            @else
                                N/A
                                <img src="{{asset($imagePath)}}" style="width:20px; float:left; margin-right: 10%">
                            @endif

                        </td>
                        <td>
                            <?php
                            if ($bill->payment_status == 'paid') {
                                $laundryStatusArray = ['processing' => 'Processing', 'completed' => 'Completed', 'delivered' => 'Delivered'];
                            } else {
                                $laundryStatusArray = ['processing' => 'Processing', 'completed' => 'Completed'];
                            }
                            ?>
                            {!! Form::select('laundry_status',$laundryStatusArray, $bill->laundry_status, ['class'=>'laundry-status', 'data-bill'=>$bill->id]) !!}
                        </td>
                        <td>{{!empty($bill->nepali_date) ? $bill->nepali_date : ''}}</td>
                        <td>{{!empty($bill->note) ? $bill->note : ''}}</td>
                        @if(auth()->user()->role == 'admin')
                            {{--@if($bill->payment_status != 'paid')--}}
                            <td><a href="{{route('employee.show',  $bill->user->id)}}">{{$bill->user->username}} </a>
                            </td>
                            {{--@endif--}}
                        @endif
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
                                            <div class="col-md-5"> Estimate no. {{$bill->id}}</div>

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
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-outline-primary">Pay</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>No Bills Found</tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
