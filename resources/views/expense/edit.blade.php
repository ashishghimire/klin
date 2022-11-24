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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit expense') }}
        </h2>
    </x-slot>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{--{{dd($employees->pluck('name', 'id'))}}--}}


        {!! Form::model($expense, ['route' => ['expense.update', $expense], 'method'=>'PATCH']) !!}
        <div class="mb-3 row">
            {!! Form::label('nepali_date', 'Date', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('nepali_date', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('txn_no', 'TXN No', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('txn_no', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('category', 'Category', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                @if(strtoupper($expense->category) == 'SALARY' || strtoupper($expense->category) == 'ALLOWANCE' || strtoupper($expense->category) == 'LUNCH' || strtoupper($expense->category) == 'CREDITED/ADJUSTED')
                    <input name="category" value="{{$expense->category}}" class="form-select form-select-sm" readonly>
                @else
                    {!! Form::select('category',  $categories->pluck('name', 'name') , old('category'), ['placeholder' => 'Select a category', 'class' => 'form-select form-select-sm category']) !!}
                @endif
            </div>
        </div>

        <div
            class="mb-3 row employee-wrap {{strtoupper($expense->category)  != 'SALARY' && strtoupper($expense->category) != 'ALLOWANCE' && strtoupper($expense->category) != 'LUNCH' && strtoupper($expense->category) != 'CREDITED/ADJUSTED' ? 'd-none' : ''}}">
            {!! Form::label('employee_id', 'Select Employee', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::select('employee_id',  $employees->pluck('name', 'id') , strtoupper($expense->category) == 'SALARY' || strtoupper($expense->category) == 'ALLOWANCE' || strtoupper($expense->category) == 'LUNCH' || strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? $employee->id : null, ['class' => 'form-select form-select-sm employee', strtoupper($expense->category) == 'SALARY' || strtoupper($expense->category) == 'ALLOWANCE' || strtoupper($expense->category) == 'LUNCH' || strtoupper($expense->category) == 'CREDITED/ADJUSTED' ? 'required' : '']) !!}
            </div>
        </div>

        <div class="mb-3 row">
            {!! Form::label('details', 'Details', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('details', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('amount', 'Amount', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::number('amount', null, ['class' => 'form-control-plaintext', 'min'=>'0', 'required']) !!}
            </div>
        </div>

        <hr>
        <hr>
        <br>
        <div class="mb-3 row">
            {!! Form::label('mode', 'Mode', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::select('mode', $modes , old('mode'), ['class' => 'form-select form-select-sm']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('payee', 'Payee', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('payee', null, ['class' => 'form-control-plaintext']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('receiver', 'Received By', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('receiver', null, ['class' => 'form-control-plaintext']) !!}
            </div>
        </div>

        {!! Form::submit('Submit', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}

        @if(auth()->user()->role == 'admin')
            <div class="float-end">
                {{ Form::open(['url' => route('expense.destroy', $expense), 'method' => 'delete']) }}
                {!! Form::submit('Delete Expense', ['class' => 'btn btn-outline-danger', 'onclick' =>'OnClickNextPage(event)']) !!}
                {{ Form::close() }}
            </div>
        @endif
    </div>

</x-app-layout>


