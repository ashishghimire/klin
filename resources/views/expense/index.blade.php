<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">

    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            function OnClickNextPage(event) {
                var result = confirm("Are you sure ?");
                if (!result) {
                    event.preventDefault(); // prevent event when user cancel
                }
            }

            $(document).ready(function () {
                $('#expense-info').DataTable({
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
                        },
                        {
                            "searchable": true,
                            "orderable": true,
                            "targets": 0,
                            "type": 'date'
                        }],
                    "order": [[0, 'desc']],
                });

            });
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

                @if(request()->route()->getName() == "expense.index")
                    {!! Form::text('startDate', !empty($startDateNepali) ? $startDateNepali : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15']) !!}

                    {!! Form::text('endDate', !empty($endDateNepali) ? $endDateNepali : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30']) !!}
                @else
                    {!! Form::text('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15']) !!}

                    {!! Form::text('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30']) !!}

                @endif

                {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table id="expense-info" class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Payment Mode</th>
                        <th>Details</th>
                        <th>Txn No</th>
                        <th>Added By</th>
                        @if(auth()->user()->role == 'admin')
                            <th class="no-sort no-search">Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($expenses->sortByDesc('created_at') as $expense)
                        <tr>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{!empty($expense->nepali_date) ? $expense->nepali_date : '-'}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{round(($expense->amount), 2)}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{$expense->category}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{$expense->mode}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{$expense->details}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{!empty($expense->txn_no) ? $expense->txn_no : '-'}}</span></td>
                            <td><span {{strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{$expense->user->name}}</span></td>
                            @if(auth()->user()->role == 'admin')
                                <td><a class="btn-sm btn-outline-dark"
                                       href="{{route('expense.edit',  $expense)}}">Edit </a>
                                    {{--{{ Form::open(['url' => route('expense.destroy', $expense), 'method' => 'delete']) }}--}}
                                    {{--<button class="btn-sm btn-outline-danger" onclick=OnClickNextPage(event)>Delete--}}
                                    {{--</button>--}}
                                    {{--{{ Form::close() }}--}}
                                </td>
                            @endif
                        </tr>

                    @empty
                        <tr>No Expenses Found</tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td><strong>Amount: </strong>{{round($calculation['total'], 2)}}</td>
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
                                        @forelse($calculation as $key=>$value)
                                            @if(strtoupper($key) != 'TOTAL' && !empty($value))
                                                <div class="row">
                                                    <div class="col-md-4"><span {{strtoupper($key) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{$key}}</span></div>
                                                    <div class="col-md-4 ms-auto"><span {{strtoupper($key) == 'CREDITED/ADJUSTED' ? 'style=color:red;' : ''}}>{{round($value,2)}}</span></div>
                                                </div>
                                            @endif
                                        @empty
                                            No data available
                                        @endforelse

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
            </div>
        </div>
    </div>
</x-app-layout>

