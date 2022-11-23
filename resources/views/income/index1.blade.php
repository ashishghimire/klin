<x-app-layout>

    @section('styles')
        <style>
            th, td {
                /*font-weight:normal;*/
                padding: 5px;
                text-align: center;
                vertical-align: bottom;
            }

            th {
                font-weight: bold;
                background: #00B0F0;
            }

            tr + tr th, tbody th {
                background: #A6C48A;
            }

            tr + tr, tbody {
                text-align: center;
            }

            table, th, td {
                border: solid 1px;
                border-collapse: collapse;
                table-layout: fixed;
            }
        </style>
    @stop
    {{--@section('scripts')--}}
    {{--<script src="{{asset('js/moment.min.js')}}"></script>--}}
    {{--<script src="{{asset('js/daterangepicker.js')}}"></script>--}}
    {{--<script>--}}
    {{--$(document).ready(function () {--}}
    {{--$('input[name="datefilter"]').daterangepicker({--}}
    {{--autoUpdateInput: false,--}}
    {{--applyButtonClasses: 'btn btn-outline-primary',--}}
    {{--locale: {--}}
    {{--cancelLabel: 'Clear'--}}
    {{--}--}}
    {{--});--}}

    {{--$('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {--}}
    {{--$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));--}}
    {{--});--}}

    {{--$('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {--}}
    {{--$(this).val('');--}}
    {{--});--}}


    {{--});--}}
    {{--</script>--}}
    {{--@stop--}}

    <x-slot name="header">
        <a href="{{route('income-export')}}">
            <small>Download Data</small>
        </a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Income Statement
        </h2>
    </x-slot>

    @if(Session::has('error'))
        <div class="alert alert-danger">
            <h4>{{session('error')}}</h4>
        </div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="container">
                <form action={{route('income1.search1')}} method="GET" role="search" class="search">
                    {{ csrf_field() }}
                    Get income statement for

                    {!! Form::text('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15', 'required']) !!}

                    {!! Form::text('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30', 'required']) !!}

                    {{--{!! Form::text('datefilter', null, ['autocomplete'=>'off', 'placeholder' => 'Select date', 'required']) !!}--}}

                    {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
                </form>
            </div>
            @if(empty(count($bills)))
                No Statement Found
            @endif
            <div style="padding-top: 3%;" class="{{empty(count($bills)) ? 'd-none' : ''}}">
                <table id="customer-info" style="width:100%;">
                    <thead>
                    <tr>
                        <th rowspan="2">Date</th>
                        <th colspan="4">Paid</th>
                        <th rowspan="2" style="background:#D72638">Unpaid</th>
                        <th rowspan="2">Total Sales</th>
                    </tr>
                    <tr>
                        <th>Cash</th>
                        <th>Fonepay</th>
                        <th>Esewa</th>
                        <th style="background:#F6AE2D">Reward Pay</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($bills->sortByDesc('nepali_date') as $bill)
                        {{--<tr {{$bill->payment_mode == 'reward points' ? 'class=table-danger': ''}}>--}}

                        <tr>
                            <td>{{$bill->nepali_date}}</td>
                            <td>{{$bill->cash}}</td>
                            <td>{{$bill->fonepay}}</td>
                            <td>{{$bill->esewa}}</td>
                            <td>{{$bill->reward_pay}}</td>
                            <td>{{$bill->unpaid}}</td>
                            <td>{{round($bill->total-$bill->reward_pay-$bill->unpaid, 2)}}</td>
                        </tr>

                    @empty
                        <tr>No Statement Found</tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr style="font-weight:bold;
                background:#00B0F0;">
                        <td>Total</td>
                        <td>{{$cash}}</td>
                        <td>{{$fonepay}}</td>
                        <td>{{$esewa}}</td>
                        <td style="background:#F6AE2D">{{$rewardPay}}</td>
                        <td style="background:#D72638">{{$unpaid}}</td>
                        <td>{{$total}}</td>
                    </tr>

                    </tfoot>

                </table>
            </div>
        </div>
    </div>
    {{--    {{$bills->withQueryString()->links()}}--}}
</x-app-layout>

