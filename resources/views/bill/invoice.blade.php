<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">
        <style>
            .dataTables_filter {
                float: left !important;
            }
        </style>
    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('#customer-info').DataTable({
                    processing: true,
                    serverSide: true,
                    "bLengthChange": false,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    },
                    ajax: "{{ route('customer.index') }}",
                    pageLength: 5,
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'address', name: 'address'},
                        {data: 'phone', name: 'phone'},
                        {data: 'billing', name: 'billing', orderable: false, searchable: false},
                    ]
                });
            });
        </script>
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ "Create Invoice" }}
        </h2>
    </x-slot>
    <div class="container container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="mb-3 row">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                        Create a new customer
                                    </h2>
                                </div>
                                {!! Form::open(['route' => 'customer.store']) !!}
                                <input type="hidden" name="billing" value="{{true}}">
                                <div class="mb-3 row">
                                    {!! Form::label('phone', 'Phone Number', ['class' => 'col-sm-5 col-form-label']) !!}

                                    <div class="col-sm">
                                        {!! Form::text('phone', null, ['class' => 'form-control-plaintext', 'required']) !!}
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    {!! Form::label('name', 'Name', ['class' => 'col-sm-5 col-form-label']) !!}

                                    <div class="col-sm">
                                        {!! Form::text('name', null, ['class' => 'form-control-plaintext', 'required']) !!}
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    {!! Form::label('address', 'Address', ['class' => 'col-sm-5 col-form-label']) !!}

                                    <div class="col-sm">
                                        {!! Form::text('address', null, ['class' => 'form-control-plaintext']) !!}
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    {!! Form::label('password', 'Create Password', ['class' => 'col-sm-5 col-form-label']) !!}

                                    <div class="col-sm">
                                        {!! Form::text('password', 'klincustomer123', ['class' => 'form-control-plaintext']) !!}
                                    </div>
                                </div>
                                <div class="mb-3 row float-sm-start">
                                    {!! Form::submit('Continue to billing', ['class' => 'btn btn-outline-primary']); !!}
                                </div>
                            </div>


                            <div class="col-sm">
                                <div class="mb-3 row">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                        Search existing customers
                                    </h2>
                                </div>
                                <table id="customer-info" class="table table-striped" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Billing</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

</x-app-layout>


