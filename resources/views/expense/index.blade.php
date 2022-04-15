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
        @if(auth()->user()->role == 'admin')
            <a href="{{route('expense-export')}}">
                <small>Download All Data</small>
            </a>
        @endif
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Expense Statement for {{$date}}
        </h2>
        <br>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif
    <div class="container">
        <form action={{route('expense.search')}} method="GET" role="search" class="search">
            {{ csrf_field() }}
            Get expense statement for

            {!! Form::text('datefilter', null, ['autocomplete'=>'off', 'placeholder' => 'Select date', 'required']) !!}

            {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
        </form>
    </div>

    <table id="customer-info" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Txn No</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Details</th>
            <th>Added By</th>
        </tr>
        </thead>
        <tbody>

        @forelse($expenses->sortByDesc('created_at') as $expense)
            <tr>
                <td>{{!empty($expense->txn_no) ? $expense->txn_no : '-'}}
                </td>
                <td>{{!empty($expense->created_at) ? date("Y-m-d", strtotime($expense->created_at)) : '-'}}</td>
                <td>{{round(($expense->amount), 2)}}</td>
                <td>{{$expense->category}}</td>
                <td>{{$expense->details}}</td>
                <td>{{$expense->user->name}}</td>
            </tr>

        @empty
            <tr>No Expenses Found</tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td><strong>Total</strong></td>
            <td><strong>Amount: </strong>{{round($total, 2)}}</td>
            @if(auth()->user()->role == 'admin')
                <td>
                    <small><a href="#" data-bs-toggle="modal"
                              data-bs-target="#expenseDetails">Details</a></small>
                </td>
            @endif
        </tr>

        <div class="modal fade" id="expenseDetails" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">Electricity</div>
                                <div class="col-md-4 ms-auto">{{round($electricity,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Detergent</div>
                                <div class="col-md-4 ms-auto">{{round($detergent,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Rent</div>
                                <div class="col-md-4 ms-auto">{{round($rent,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Petrol</div>
                                <div class="col-md-4 ms-auto">{{round($petrol,2)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-red-600">Miscellaneous</div>
                                <div class="col-md-4 ms-auto text-red-600">{{round($misc,2)}}</div>
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

        </tfoot>

    </table>
    {{$expenses->withQueryString()->links()}}
</x-app-layout>

