<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Service') }}
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


        {!! Form::open(['route' => 'service.store']) !!}
        <div class="mb-3 row">
            {!! Form::label('name', 'Service Name', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('name', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
        <div class="mb-3 row">
            {!! Form::label('rate', 'Rate', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-5">
                {!! Form::text('rate', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
            per
            <div class="col-sm-2">
                {!! Form::select('unit', ['kg'=>'kg', 'pc' => 'pc'], null, ['required']) !!}
            </div>
        </div>

        {!! Form::submit('Submit', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}
    </div>

</x-app-layout>


