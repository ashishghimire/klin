<x-app-layout>

    @section('scripts')
        <script src="{{asset('js/script.js')}}"></script>
    @stop

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit bill for ').$bill->customer->name }}
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
        <br>
        {!! Form::model($bill, ['route' => ['bill.update', $bill->id], 'class' => 'billing-form', 'method' => 'PATCH']) !!}

        <div class="mb-3 row">
            {!! Form::label('estimate_no', 'Estimate Number', ['class' => 'col-sm-2 col-form-label']) !!}

            <div class="col-sm-6">
                {!! Form::text('estimate_no', null, ['class' => 'form-control form-control-sm', 'autofocus', 'autocomplete'=>'off', 'placeholder' => 'Enter estimate no.', 'required']) !!}
            </div>
        </div>
        <br>
        <div class="mb-3 row">
            <div class="services-section">
                <div class="service-wrapper">
                    <?php $counter = 0;?>
                    @forelse (old('service_details', []) as $i => $serviceDetail)
                        <div class="row g-3 service-individual">

                            <div class="col-sm-2">
                                @if($i > 0)
                                    <button type="button" class="btn btn-outline-danger remove-service">x</button>
                                @else
                                    Service
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <select name="service_details[{{$i}}][service_type]"
                                        class="form-select form-select-sm service-type"
                                        aria-label="Select service type"
                                        required>
                                    <option value="">Select service type</option>
                                    @forelse($services as $service)
                                        <option value="{{$service->name}}" data-rate="{{$service->rate}}"
                                                data-unit="{{$service->unit}}" {{$serviceDetail['service_type'] == $service->name ? 'selected' : ''}}>{{$service->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-sm">
                                <input type="number" name="service_details[{{$i}}][quantity]"
                                       class="form-control quantity float-only"
                                       placeholder="Weight/Pcs" aria-label="Weight/Pcs"
                                       value="{{$serviceDetail['quantity']}}" min="0" step=".01" required>
                            </div>
                            <div class="col-sm rate-input">
                                <input type="text" readonly class="form-control rate-dynamic" placeholder="Rate"
                                       aria-label="Rate" name="service_details[{{$i}}][rate]"
                                       value="{{$serviceDetail['rate']}}">
                            </div>
                            <div class="col-sm">
                                <span class="unit-dynamic">per {{$serviceDetail['unit']}}</span>
                            </div>
                            <input type="hidden" class="unit-dynamic" name="service_details[{{$i}}][unit]"
                                   value="{{$serviceDetail['unit']}}">
                        </div>
                        <?php $counter++;?>
                    @empty
                        @forelse ($bill->service_details as $j=>$serviceDetail)
                            <div class="row g-3 service-individual">
                                <div class="col-sm-2">
                                    @if($j > 0)
                                        <button type="button" class="btn btn-outline-danger remove-service">x</button>
                                    @else
                                        Service
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <select name="service_details[{{$j}}][service_type]"
                                            class="form-select form-select-sm service-type"
                                            aria-label="Select service type"
                                            required>
                                        <option value="">Select service type</option>
                                        @forelse($services as $service)
                                            <option value="{{$service->name}}" data-rate="{{$service->rate}}"
                                                    data-unit="{{$service->unit}}" {{$service->name == $serviceDetail['service_type'] ? 'selected' : ''}}>{{$service->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <input type="number" name="service_details[{{$j}}][quantity]"
                                           class="form-control quantity float-only"
                                           placeholder="Weight/Pcs"
                                           aria-label="Weight/Pcs" min="0" step=".01"
                                           value="{{$serviceDetail['quantity']}}" required>
                                </div>
                                <div class="col-sm rate-input">
                                    <input type="text" name="service_details[{{$j}}][rate]" readonly
                                           class="form-control rate-dynamic" placeholder="Rate"
                                           aria-label="Rate" value="{{$serviceDetail['rate']}}">
                                </div>
                                <div class="col-sm">
                                    <span class="unit-dynamic">per {{$serviceDetail['unit']}}</span>
                                </div>
                                <input type="hidden" class="unit-dynamic" name="service_details[{{$j}}][unit]"
                                       value="{{$serviceDetail['unit']}}">
                            </div>
                            <?php $counter++;?>
                        @empty
                            <div class="row g-3 service-individual">
                                <div class="col-sm-2">
                                    @if($j > 0)
                                        <button type="button" class="btn btn-outline-danger remove-service">x</button>
                                    @else
                                        Service
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <select name="service_details[0][service_type]"
                                            class="form-select form-select-sm service-type"
                                            aria-label="Select service type"
                                            required>
                                        <option value="">Select service type</option>
                                        @forelse($services as $service)
                                            <option value="{{$service->name}}" data-rate="{{$service->rate}}"
                                                    data-unit="{{$service->unit}}">{{$service->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <input type="number" name="service_details[0][quantity]"
                                           class="form-control quantity float-only"
                                           placeholder="Weight/Pcs"
                                           aria-label="Weight/Pcs" min="0" step=".01"
                                           required>
                                </div>
                                <div class="col-sm rate-input">
                                    <input type="text" name="service_details[0][rate]" readonly
                                           class="form-control rate-dynamic" placeholder="Rate"
                                           aria-label="Rate">
                                </div>
                                <div class="col-sm">
                                    <span class="unit-dynamic"></span>
                                </div>
                                <input type="hidden" class="unit-dynamic" name="service_details[0][unit]" value="kg">
                            </div>
                        @endforelse
                    @endforelse
                </div>
                <button type="button" name="add" id="add-service" class="btn btn-outline-primary float-end"
                        data-counter="{{$counter}}">Add More
                </button>
            </div>
        </div>
        <div class="mb-3 amount row">

            <div class="col-sm-3">
                <h1 style="font-size: 30px;">Amount: Rs. <span
                        class="badge bg-secondary amount-calculated">{{$bill->amount}}</span>
                </h1>
                <input type="hidden" name="amount" value="{{$bill->amount}}">
            </div>
            <div class="col-sm">
                <button type="button" class="btn btn-outline-secondary calculate-amount float-sm-start">Calculate
                </button>
            </div>

        </div>
        <br>
        <div class="mb-3 row">
            {!! Form::label('payment_mode', 'Advance Payment', ['class' => 'col-sm-2 col-form-label payment']) !!}

            <div class="col-sm-6">
                {!! Form::select('payment_mode',  $paymentModes->pluck('name', 'name') , null, ['placeholder' => 'Not paid', 'class' => 'form-select form-select-sm payment']) !!}
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                {!! Form::number('paid_amount', null, ['class' => 'form-control form-control-sm float-only', $bill->payment_status == 'unpaid' || $bill->payment_mode == 'reward points' ? 'hidden' : '', 'step'=>'.01', 'placeholder'=>'Amount (Rs.)','autocomplete'=>'off', 'required'])   !!}
            </div>
        </div>


        {!! Form::submit('Submit', ['class' => 'btn btn-outline-primary']); !!}

        {!! Form::close() !!}
        <br>
    </div>

    <div class="additional-service d-none">
        <div class="row g-3 service-individual">
            <div class="col-sm-2">
                <button type="button" class="btn btn-outline-danger remove-service">x</button>
            </div>
            <div class="col-sm-6">
                <select class="form-select form-select-sm service-type" aria-label="Select service type"
                        required>
                    <option value="">Select service type</option>
                    @forelse($services as $service)
                        <option value="{{$service->name}}" data-rate="{{$service->rate}}"
                                data-unit="{{$service->unit}}">{{$service->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-sm">
                <input type="number" class="form-control quantity float-only" placeholder="Weight/Pcs"
                       aria-label="Weight/Pcs"
                       min="0" step=".01" required>
            </div>
            <div class="col-sm rate-input">
                <input type="text" readonly class="form-control rate-dynamic" placeholder="Rate" aria-label="Rate">
            </div>
            <div class="col-sm">
                <span class="unit-dynamic"></span>
            </div>
            <input type="hidden" class="unit-dynamic" value="kg">
        </div>
    </div>

</x-app-layout>


