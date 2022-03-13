<x-app-layout>

    @section('scripts')
        <script>
            $(document).on('click', '#add-service', function () {
                var additionalService = $('.additional-service').html();
                $('.service-wrapper').append(additionalService);
            });

            $(document).on('click', '.remove-service', function () {
                $(this).parent().closest('.service-individual').remove();
            });

            $(document).on('change', '.service-type', function () {

                var rate = $(this).find(':selected').data('rate');
                var unit = $(this).find(':selected').data('unit');
                var value = rate+" per "+unit;
                $(this).closest('.service-individual').find('input.rate-dynamic').val(value);
            });

        </script>
    @stop

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create bill') }}
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

        <div class="service-wrapper">
            <div class="row g-3 service-individual">
                <div class="col-sm-1"></div>
                <div class="col-sm-7">
                    <select name="service_type" class="form-control service-type" aria-label="Select service type" required>
                        <option value="">Select service type</option>
                        @forelse($services as $service)
                            <option value="{{$service->id}}" data-rate="{{$service->rate}}"
                                    data-unit="{{$service->unit}}">{{$service->name}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-sm">
                    <input type="number" class="form-control" placeholder="Weight/Pcs" aria-label="Weight/Pcs">
                </div>
                <div class="col-sm rate-input">
                    <input type="text" disabled class="form-control rate-dynamic" placeholder="Rate" aria-label="Rate">
                </div>
            </div>
        </div>
        <button type="button" name="add" id="add-service" class="btn btn-outline-primary float-end">+</button>


        {{--{!! Form::open(['route' => ['customer.bill.store', $customer->id]]) !!}--}}
        {{--<div class="mb-3 row">--}}
        {{--{!! Form::label('service_type', 'Service Type', ['class' => 'col-sm-2 col-form-label']) !!}--}}

        {{--<div class="col-sm-6">--}}
        {{--<select name="service_type" class="col-sm-12" required>--}}
        {{--<option value="">Select service type</option>--}}
        {{--@forelse($services as $service)--}}
        {{--<option value="{{$service->id}}" data-rate="{{$service->rate}}" data-unit="{{$service->unit}}">{{$service->name}}</option>--}}
        {{--@empty--}}
        {{--@endforelse--}}
        {{--</select>--}}
        {{--</div>--}}
        {{--<div class="col-sm-4">Rate:</div>--}}

        {{--</div>--}}

        {{--<div class="mb-3 row">--}}
        {{--{!! Form::label('estimate_no', 'Estimate Number', ['class' => 'col-sm-2 col-form-label']) !!}--}}

        {{--<div class="col-sm-10">--}}
        {{--{!! Form::text('estimate_no', null, ['class' => 'form-control-plaintext']) !!}--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="mb-3 row">--}}
        {{--{!! Form::label('estimate_no', 'Estimate Number', ['class' => 'col-sm-2 col-form-label']) !!}--}}

        {{--<div class="col-sm-10">--}}
        {{--{!! Form::text('estimate_no', null, ['class' => 'form-control-plaintext']) !!}--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="mb-3 row">--}}
        {{--{!! Form::label('phone', 'Phone Number', ['class' => 'col-sm-2 col-form-label']) !!}--}}

        {{--<div class="col-sm-10">--}}
        {{--{!! Form::text('phone', null, ['class' => 'form-control-plaintext', 'required']) !!}--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="mb-3 row">--}}
        {{--{!! Form::label('email', 'Email', ['class' => 'col-sm-2 col-form-label']) !!}--}}

        {{--<div class="col-sm-10">--}}
        {{--{!! Form::text('email', null, ['class' => 'form-control-plaintext']) !!}--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--{!! Form::submit('Submit', ['class' => 'btn btn-outline-primary']); !!}--}}

        {{--{!! Form::close() !!}--}}
    </div>

    <div class="additional-service d-none">
        <div class="row g-3 service-individual">
            <div class="col-sm-1">
                <button type="button" class="btn btn-outline-danger remove-service">-</button>
            </div>
            <div class="col-sm-7">
                <select name="service_type" class="form-control service-type" aria-label="Select service type" required>
                    <option value="">Select service type</option>
                    @forelse($services as $service)
                        <option value="{{$service->id}}" data-rate="{{$service->rate}}"
                                data-unit="{{$service->unit}}">{{$service->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-sm">
                <input type="number" class="form-control" placeholder="Weight/Pcs" aria-label="Weight/Pcs">
            </div>
            <div class="col-sm rate-input">
                <input type="text" disabled class="form-control rate-dynamic" placeholder="Rate" aria-label="Rate">
            </div>
        </div>
    </div>

</x-app-layout>


