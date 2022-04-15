<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Employee
        </h2>
    </x-slot>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['route' => ['employee.store']]) !!}
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Name -->
            <div>
                <label class="block font-medium text-sm text-gray-700" for="name">
                    Name
                </label>

                {!! Form::text('name', null, ['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full', 'autofocus', 'autocomplete'=>'off', 'required']) !!}

            </div>

            <!-- Username -->
            <div class="mt-4">
                <label class="block font-medium text-sm text-gray-700" for="username">
                    Username
                </label>

                {!! Form::text('username', null, ['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full', 'required']) !!}

            </div>

            <!-- Password -->
            <div class="mt-4">
                <label class="block font-medium text-sm text-gray-700" for="password">
                    Password
                </label>

                {!! Form::password('password', ['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full', 'id'=>'password', 'required']) !!}

            </div>

            <div class="flex items-center justify-end mt-4">

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                    Register
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</x-app-layout>
