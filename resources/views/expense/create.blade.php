<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add expense') }}
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
                {!! Form::select('category',  $categories->pluck('name', 'name') , old('category'), ['placeholder' => 'Select a category', 'class' => 'form-select form-select-sm']) !!}
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
                {!! Form::number('amount', null, ['class' => 'form-control-plaintext']) !!}
            </div>
        </div>
        {!! Form::submit('Submit', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}
    </div>

</x-app-layout>

