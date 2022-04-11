<x-app-layout>

    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    @stop
    @section('scripts')
        <script src="{{asset('js/moment.min.js')}}"></script>
        <script src="{{asset('js/daterangepicker.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('input[name="datefilter"]').daterangepicker({
                    autoUpdateInput: false,
                    applyButtonClasses: 'btn btn-outline-primary',
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                });

                $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
                    $(this).val('');
                });


            });
        </script>
    @stop

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Income Statement for {{$date}}
        </h2>
        <br>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif
    <div class="container">
        <form action={{route('income.search')}} method="GET" role="search" class="search">
            {{ csrf_field() }}
            Get income statement for

            {!! Form::text('datefilter', null, ['autocomplete'=>'off', 'placeholder' => 'Select date', 'required']) !!}

            {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
        </form>
    </div>

    <table id="customer-info" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Estimate No.</th>
            <th>Date</th>
            <th>Income</th>
            <th>13% VAT</th>
            <th>Amount</th>
            <th>Payment Status</th>
            <th>Payment Mode</th>
            {{--<th>Action</th>--}}
        </tr>
        </thead>
        <tbody>

        @forelse($bills->sortByDesc('created_at') as $bill)
            <tr {{$bill->payment_mode == 'reward points' ? 'class=table-danger': ''}}>
                <td>
                    <a href="{{route('customer.bill.show', [$bill->customer->id, $bill->id])}}"> {{$bill->estimate_no}}</a>
                </td>
                <td>{{!empty($bill->created_at) ? date("Y-m-d", strtotime($bill->created_at)) : '-'}}</td>
                <td>{{round(($bill->amount/1.13), 2)}}</td>
                <td>{{round(($bill->amount/1.13 *0.13), 2)}}</td>
                <td>{{round(($bill->amount), 2)}}</td>

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
            </tr>

        @empty
            <tr>No Statement Found</tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td><strong>Total</strong></td>
            <td><strong>Income: </strong>{{round($income, 2)}}</td>
            <td><strong>13% VAT: </strong>{{round($vat, 2)}}</td>
            <td><strong>Amount: </strong>{{round($total, 2)}}</td>
            <td></td>
            <td><small><a href="#" data-bs-toggle="modal"
                          data-bs-target="#incomeDetails">Detail</a></small></td>
        </tr>
        <!-- Modal -->
        <div class="modal fade" id="incomeDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">Cash</div>
                                <div class="col-md-4 ms-auto">{{round($cash,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Khalti</div>
                                <div class="col-md-4 ms-auto">{{round($khalti,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Esewa</div>
                                <div class="col-md-4 ms-auto">{{round($esewa,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Reward points</div>
                                <div class="col-md-4 ms-auto">{{round($rewardPay,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-red-600">Unpaid</div>
                                <div class="col-md-4 ms-auto text-red-600">{{round($unpaid,2)}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>Cash</td>--}}
        {{--<td>{{$cash}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>Khalti</td>--}}
        {{--<td>{{$khalti}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>Esewa</td>--}}
        {{--<td>{{$esewa}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>Reward points</td>--}}
        {{--<td>{{$rewardPay}}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>Unpaid</td>--}}
        {{--<td>{{$unpaid}}</td>--}}
        {{--</tr>--}}
        </tfoot>

    </table>
    {{$bills->withQueryString()->links()}}
</x-app-layout>

