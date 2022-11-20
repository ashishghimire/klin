<x-app-layout>
    @section('scripts')
        <script>
            function OnClickNextPage(event) {
                var result = confirm("Are you sure ?");
                if (!result) {
                    event.preventDefault(); // prevent event when user cancel
                }
            }
        </script>
    @stop


    <x-slot name="header">
        @if(auth()->user()->role == 'admin')
            <a href="{{route('expense-export')}}">
                <small>Download Data</small>
            </a>
        @endif
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Expense Statement
        </h2>
        <br>
    </x-slot>

    @if(Session::has('error'))
        <div class="alert alert-danger">
            <h4>{{session('error')}}</h4>
        </div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action={{route('expense.search')}} method="GET" role="search" class="search">
                {{ csrf_field() }}
                {!! Form::select('category', \App\Models\ExpenseCategory::all()->pluck('name', 'name'), !empty(request()->get('category')) ? request()->get('category') : null, ['placeholder' => 'All Categories']) !!}

                {{--{!! Form::text('datefilter', null, ['autocomplete'=>'off', 'placeholder' => 'Select date', 'required']) !!}--}}

                {!! Form::text('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15', 'required']) !!}

                {!! Form::text('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30', 'required']) !!}


                {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table id="customer-info" class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th>Txn No</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Added By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($expenses->sortByDesc('created_at') as $expense)
                        <tr>
                            <td>{{!empty($expense->txn_no) ? $expense->txn_no : '-'}}
                            </td>
                            <td>{{!empty($expense->nepali_date) ? $expense->nepali_date : '-'}}</td>
                            <td>{{round(($expense->amount), 2)}}</td>
                            <td>{{$expense->category}}</td>
                            <td>{{$expense->details}}</td>
                            <td>{{$expense->user->name}}</td>
                            <td><a class="btn-sm btn-outline-dark" href="{{route('expense.edit',  $expense)}}">Edit </a>
                                @if(auth()->user()->role == 'admin')
                                    {{ Form::open(['url' => route('expense.destroy', $expense), 'method' => 'delete']) }}
                                    <button class="btn-sm btn-outline-danger" onclick=OnClickNextPage(event)>Delete
                                    </button>
                                    {{ Form::close() }}
                                @endif
                            </td>
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
                                    <button type="button" class="btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    </tfoot>

                </table>
                {{$expenses->withQueryString()->links()}}
            </div>
        </div>
    </div>
</x-app-layout>

