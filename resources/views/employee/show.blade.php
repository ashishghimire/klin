@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">

@stop
@section('scripts')
    <script src="{{asset('js/jQueryDatatables.js')}}"></script>
    <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#salary-info').DataTable({
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
                "order": [[0, 'desc']],
            });

        });
    </script>
@stop

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$employee->name}} Salary Data
        </h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="row">
                <div class="col-md-6">
                    <form action={{route('employee.search', $employee)}} method="GET" role="search" class="search">
                        {{ csrf_field() }}

                        {!! Form::text('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15', 'required']) !!}

                        {!! Form::text('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30', 'required']) !!}

                        {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
                    </form>
                </div>

                <div class="col-md-2">

                    <a href="#" data-bs-toggle="modal"
                       data-bs-target="#salaryDetails" class="btn btn-primary">Total</a>

                </div>

            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table id="salary-info" class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <td>
                                {{$salary->nepaliDate}}
                            </td>
                            <td>
                                {{$salary->amount}}
                            </td>
                            <td>
                                {{strtoupper($salary->type) == 'SALARY' ? 'Cash Withdrawn' : $salary->type}}
                            </td>
                            <td>
                                {{$salary->details}}
                            </td>
                        </tr>
                    @empty
                        No data available
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="modal fade" id="salaryDetails" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="container-fluid">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align : middle; text-align:center;">Credited</th>
                                    <th colspan="3" style="vertical-align : middle; text-align:center;">Withdrawn</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align : middle; text-align:center;">Lunch</th>
                                    <th style="vertical-align : middle; text-align:center;">Salary</th>
                                    <th style="vertical-align : middle; text-align:center;">Allowance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="vertical-align : middle; text-align:center;">{{$totalCredited}}</td>
                                    <td style="vertical-align : middle; text-align:center;">{{$totalLunch}}</td>
                                    <td style="vertical-align : middle; text-align:center;">{{$totalSalary}}</td>
                                    <td style="vertical-align : middle; text-align:center;">{{$totalAllowance}}</td>
                                </tr>
                                </tbody>
                                <tfoot class="table-primary">
                                <tr>
                                    <td style="vertical-align : middle; text-align:center;">{{$totalCredited}}</td>
                                    <td colspan="3"
                                        style="vertical-align : middle; text-align:center;">{{$totalLunch + $totalSalary + $totalAllowance}}</td>
                                </tr>
                                </tfoot>
                            </table>
                            {{--<div class="row">--}}
                            {{--<div class="col-md-4">Credited/Adjusted</div>--}}
                            {{--<div class="col-md-4 ms-auto">{{$totalCredited}}</div>--}}
                            {{--</div>--}}
                            {{--<div class="row">--}}
                            {{--<div class="col-md-4">Cash Withdrawn</div>--}}
                            {{--<div class="col-md-4 ms-auto">{{$totalSalary}}</div>--}}
                            {{--</div>--}}
                            {{--<div class="row">--}}
                            {{--<div class="col-md-4">Lunch</div>--}}
                            {{--<div class="col-md-4 ms-auto">{{$totalLunch}}</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <div class="col-md-5 ms-auto">Balance:</div>
                                <div
                                    class="col-md-5 ms-auto">{{$totalCredited - ($totalLunch + $totalSalary + $totalAllowance)}}</div>
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
    </div>
</x-app-layout>
