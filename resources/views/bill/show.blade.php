<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bill no. {{$bill->id}}, Customer: {{$bill->customer->name}}
        </h2>
    </x-slot>
    <div class="container">
        <div class="container mt-5 mb-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="upper p-4">
                            Bill no. {{$bill->id}}
                            <div class="d-flex justify-content-between">
                                <div class="amount"><span
                                        class="text-primary font-weight-bold">{{$bill->customer->name}}</span>
                                    <h4>{{$bill->customer->phone}}</h4>
                                    <small>{{!empty($bill->nepali_date) ? $bill->nepali_date : ''}}</small>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <div class="add"><span
                                            class="font-weight-bold d-block">{{!empty($bill->customer->address) ? $bill->customer->address : ''}}</span>
                                        <small>{{!empty($bill->customer->email) ? $bill->customer->email : ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="transaction mt-2">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-row align-items-center"><i class="fa fa-check-circle-o"></i>
                                        <span class="ml-2">Services</span></div>
                                    <span class="font-weight-bold"> Price</span>
                                </div>
                            </div>
                            <hr>
                            <br>

                            @forelse(($bill->service_details) as $detail)
                                <div class="transaction mt-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex flex-row align-items-center"><i
                                                class="fa fa-check-circle-o"></i> <span class="ml-2">{{$detail['service_type']}}
                                                : {{$detail['quantity']}} {{$detail['unit']}} * (Rs. {{$detail['rate']}}
                                                per {{$detail['unit']}})</span></div>
                                        <span
                                            class="font-weight-bold">Rs. {{round($detail['quantity']*$detail['rate'], 2)}}</span>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <div class="lower bg-primary p-4 py-5 text-white d-flex justify-content-between">
                            <div>
                                <div class="d-flex flex-column"><span>Total</span>
                                </div>
                                <h3>Rs. {{round($bill['amount'], 2)}}</h3>
                            </div>
                            <div>
                                <div class="d-flex flex-column"><span>Amount Paid</span>
                                </div>
                                <h3>Rs. {{round($bill['paid_amount'], 2)}}</h3>
                            </div>
                            <div>
                                <div class="d-flex flex-column"><span>Due Amount</span>
                                </div>
                                <h3>Rs. {{round($bill['amount'] - $bill['paid_amount'], 2)}}</h3>
                            </div>
                        </div>
                        @if(!empty($bill->note))
                            <p>Note:
                                <span>{{$bill->note}}</span>
                            </p>
                        @endif

                    </div>
                    <a class="btn btn-primary float-end" href="{{route('dashboard')}}">Go to dashboard</a>
                    <a class="btn btn-outline-secondary float-left" href="{{route('invoice.create')}}">Create new invoice</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>


