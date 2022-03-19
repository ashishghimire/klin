<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Rewards System
        </h2>
    </x-slot>


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {!! Form::model($setting, ['route' => ['rewards.store'], 'method' => 'PATCH']) !!}


<!-- Password -->
    <div>
        {!! Form::label('rewards_key', 'Rewards Key (1 Reward Point = Amount spent x Rewards Key)  Please enter value between 0 and 1') !!}
    </div>
    <div class="mt-4">
        {!! Form::number('rewards_key', null, ['min'=>0, 'step'=> .0001,'required']) !!}

    </div>

    <div class="flex justify-start mt-4">
        <x-button>
            {{ __('Confirm') }}
        </x-button>
    </div>
    {!! Form::close() !!}
</x-app-layout>
