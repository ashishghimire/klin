<x-app-layout>
    @section('scripts')
        <script>
            $(document).on('change', '.category', function () {

                if (this.value.toUpperCase() == 'SALARY' || this.value.toUpperCase() == 'LUNCH' || this.value.toUpperCase() == 'ALLOWANCE' || this.value.toUpperCase() == 'CREDITED/ADJUSTED') {
                    $('.employee-wrap').removeClass('d-none');
                    $("select.employee").attr('required', true);
                } else {
                    $('.employee-wrap').addClass('d-none');
                    $("select.employee").removeAttr('required');
                }
            });
        </script>
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add expense') }}
        </h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
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


            {!! Form::open(['route' => 'expense.store']) !!}
            <div class="mb-3 row">
                {!! Form::label('txn_no', 'TXN No', ['class' => 'col-sm-2 col-form-label']) !!}

                <div class="col-sm-10">
                    {!! Form::text('txn_no', null, ['class' => 'form-control-plaintext', 'required']) !!}
                </div>
            </div>
            <div class="mb-3 row">
                {!! Form::label('category', 'Category', ['class' => 'col-sm-2 col-form-label']) !!}

                <div class="col-sm-10">
                    {!! Form::select('category',  $categories->pluck('name', 'name') , old('category'), ['placeholder' => 'Select a category', 'class' => 'form-select form-select-sm category']) !!}
                </div>
            </div>

            <div class="mb-3 row employee-wrap d-none">
                {!! Form::label('employee_id', 'Select Employee', ['class' => 'col-sm-2 col-form-label']) !!}

                <div class="col-sm-10">
                    {!! Form::select('employee_id',  $employees->pluck('name', 'id') , old('employee_id'), ['placeholder' => 'Select Employee', 'class' => 'form-select form-select-sm employee']) !!}
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
        </div>
    </div>

</x-app-layout>


