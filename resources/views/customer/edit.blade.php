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
            {{ __('Edit customer') }}
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
        {!! Form::model($customer, ['route' => ['customer.update', $customer->id], 'method' => 'PATCH']) !!}
        <div class="mb-3 row">
            {!! Form::label('phone', 'Phone Number', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('phone', null, ['class' => 'form-control-plaintext', 'required']) !!}
            </div>
        </div>
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
            {!! Form::label('new-password', 'Create Password', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-10">
                {!! Form::text('new-password', null, ['class' => 'form-control-plaintext', 'placeholder'=>'Password Unchanged']) !!}
            </div>
        </div>
        {!! Form::submit('Update Customer', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}

        @if(auth()->user()->role == 'admin')
            <div class="float-end">
                {!! Form::open(['route'=>['customer.destroy', $customer->id], 'method'=>'DELETE']) !!}
                {!! Form::submit('Delete Customer', ['class' => 'btn btn-outline-danger','onclick' =>'OnClickNextPage(event)']); !!}

                {!! Form::close() !!}
            </div>
        @endif
    </div>

</x-app-layout>


