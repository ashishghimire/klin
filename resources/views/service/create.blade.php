<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Service') }}
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


            {!! Form::open(['route' => 'service.store']) !!}
            <div class="mb-3 row">
                {!! Form::label('name', 'Service Name', ['class' => 'col-sm-2 col-form-label']) !!}

                <div class="col-sm-3">
                    {!! Form::text('name', null, ['class' => 'form-control-plaintext', 'required']) !!}
                </div>
            </div>
            <div class="mb-3 row">

                {!! Form::label('shortcode', 'Shortcode', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-3">
                    {!! Form::text('shortcode', null, ['class' => 'form-control-plaintext', 'required']) !!}
                </div>
            </div>
            <div class="mb-3 row">
                {!! Form::label('rate', 'Rate', ['class' => 'col-sm-2 col-form-label']) !!}

                <div class="col-sm-2">
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
    </div>

</x-app-layout>


