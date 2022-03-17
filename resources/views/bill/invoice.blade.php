<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add customer') }}
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

        <div class="mb-3 row">
            {!! Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('name', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('address', 'Address', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('address', null, ['class' => 'form-control-plaintext']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('phone', 'Phone Number', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('phone', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('email', 'Email', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('email', null, ['class' => 'form-control-plaintext']) !!}
            </div>
        </div>

    </div>

</x-app-layout>

