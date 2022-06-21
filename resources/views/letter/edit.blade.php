<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Letter') }}
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
        {!! Form::model($letter, ['route' => ['letter.update',$letter], 'method' => 'PATCH']) !!}
        <div class="mb-3 row">
            {!! Form::label('to', 'To', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::textarea('to', null, ['class' => 'form-control','rows' => 2, 'style'=>'resize: none;', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('address', 'Address', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::textarea('address', null, ['class' => 'form-control','rows' => 2, 'style'=>'resize: none;', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('subject', 'Subject', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::textarea('subject', null, ['class' => 'form-control','rows' => 1, 'style'=>'resize: none;', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('body', 'Body', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::textarea('body', null, ['class' => 'form-control','rows' => 5, 'style'=>'resize: none;', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-sm-2"></div>

            {!! Form::label('signed_by', 'Signed By', ['class' => 'col-sm-1 col-form-label']) !!}

            <div class="col-sm-2">
                {!! Form::text('signed_by', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>

            {!! Form::label('designation', 'Designation', ['class' => 'col-sm-1 col-form-label']) !!}

            <div class="col-sm-2">
                {!! Form::text('designation', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>

            {!! Form::label('date', 'Date', ['class' => 'col-sm-1 col-form-label']) !!}

            <div class="col-sm-2">
                {!! Form::text('nepali_date', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>

        </div>
        {!! Form::submit('Update', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}
    </div>

</x-app-layout>


