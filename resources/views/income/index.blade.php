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
                    autoUpdateInput: true,
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
            <th>Amount</th>
            <th>Payment Status</th>
            <th>Payment Mode</th>
            {{--<th>Action</th>--}}
        </tr>
        </thead>
        <tbody>
        <?php $sum = 0.0;?>
        @forelse($bills->sortByDesc('created_at') as $bill)
            <tr>
                <td>{{$bill->estimate_no}}</a>
                </td>
                <td>{{!empty($bill->created_at) ? date("Y-m-d", strtotime($bill->created_at)) : '-'}}</td>
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
                {{--<td><a class="btn btn-outline-dark" href="{{route('bill.edit',  $bill->id)}}">Edit </a></td>--}}
            </tr>
            <?php $sum += floatval($bill->amount); ?>
        @empty
            <tr>No Statement Found</tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td>Total</td>
            <td>{{$sum}}</td>
        </tr>
        </tfoot>

    </table>
    {{$bills->withQueryString()->links()}}
</x-app-layout>
