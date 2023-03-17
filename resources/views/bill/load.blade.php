<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">

        <style>
            .loading {
                background: grey;
            }
        </style>

    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        @if($isSearch)
            @include('bill.partials.datatable-script-search')
        @else
            @include('bill.partials.datatable-script-index')
        @endif
        <script>

            $(document).on('change', '.laundry-status', function () {
                $(this).attr('disabled', 'disabled');
                $(this).addClass('loading')
                var billId = $(this).data('bill');
                var laundryStatus = $(this).val();
                var self = $(this);
                $.ajax({
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    method: "POST",
                    url: "{{url('change-laundry-status')}}/" + billId,

                    data: {laundry_status: laundryStatus},
                    success: function (data) {
                        self.removeAttr('disabled');
                        self.removeClass('loading');
                    },
                });
            });

            $(document).on('change', '.laundry-status', function () {
                $(this).attr('disabled', 'disabled');
                var billId = $(this).data('bill');
                var laundryStatus = $(this).val();
                var self = $(this);
                $.ajax({
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    method: "POST",
                    url: "{{url('change-laundry-status')}}/" + billId,

                    data: {laundry_status: laundryStatus},
                    success: function (data) {
                        self.removeAttr('disabled');
                    },
                });
            });

            $(document).on('change', '.change-laundry-status', function () {

                window.location = $(this).data('url') + '?laundry-status=' + $(this).val();
            });

        </script>

    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invoices {{ !empty(request()->get('startDate')) && !empty(request()->get('endDate')) ?  'from '.request()->get('startDate').' to '.request()->get('endDate') :  '' }}
            <a class="btn-sm btn-outline-primary" href="{{route('invoice.create')}}">Add</a>
        </h2>
        <br>
        {!! Form::label('change-laundry-status', 'Laundry Status', ['class' => 'col-sm-2 col-form-label']) !!}
        {!! Form::select('change-laundry-status', ['processing'=>'Processing', 'completed'=>'Completed', 'delivered'=>'Delivered'], !empty(request()->query->get('laundry-status')) ? request()->query->get('laundry-status') : null, ['placeholder' => 'All', 'class' => 'col-sm-2 col-form-label change-laundry-status', 'data-url' => route('bill.index')]) !!}
        <br>
        <br>
        <form action={{route('bill.search')}} method="GET" role="search" class="search">
            {{ csrf_field() }}

            {!! Form::label('startDate', 'Get invoices for', ['class' => 'col-sm-2 col-form-label']) !!}


            {!! Form::search('startDate', !empty(request()->get('startDate')) ? request()->get('startDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-15', 'required']) !!}

            {!! Form::search('endDate', !empty(request()->get('endDate')) ? request()->get('endDate') : null, ['autocomplete'=>'off', 'placeholder' => 'Eg. 2079-1-30', 'required']) !!}

            {!! Form::hidden('laundry-status', !empty(request()->query->get('laundry-status')) ? request()->query->get('laundry-status') : null) !!}

            {!! Form::submit('Search', ['class' => 'btn btn-outline-primary']); !!}
        </form>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif

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
            <table id="bill-info" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Estimate No.</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Services</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Mode</th>
                    <th class="no-sort">Laundry Status</th>
                    <th>Date</th>
                    <th>Note</th>
                    @if(auth()->user()->role == 'admin')
                        <th>Added By</th>
                    @endif
                    @if(auth()->user()->role == 'admin')
                        <th class="no-sort">Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
