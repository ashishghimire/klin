<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <a class="btn btn-primary btn-lg" href="{{route('invoice.create')}}">Create Invoice</a>

                    <div class="row">
                        <div class="col-sm-3">
                            <a href="{{route('bill.index', ['laundry-status'=>'processing'])}}">
                                <figure class="figure">
                                    <img src="{{asset('images/laundryprocessing.png')}}"
                                         class="figure-img img-fluid rounded" style="width:250px;height:250px">
                                    <figcaption
                                        class="figure-caption font-semibold text-xl text-gray-800 leading-tight">
                                        Processing: {{$processingCount}}</figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{route('bill.index', ['laundry-status'=>'completed'])}}">
                                <figure class="figure">
                                    <img src="{{asset('images/laundrycompleted.png')}}"
                                         class="figure-img img-fluid rounded"
                                         style="width:250px;height:250px">
                                    <figcaption
                                        class="figure-caption font-semibold text-xl text-gray-800 leading-tight">
                                        Completed: {{$completedCount}}</figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{route('bill.index', ['laundry-status'=>'delivered'])}}">
                                <figure class="figure">
                                    <img src="{{asset('images/laundrydelivered.png')}}"
                                         class="figure-img img-fluid rounded"
                                         style="width:250px;height:250px">
                                    <figcaption
                                        class="figure-caption font-semibold text-xl text-gray-800 leading-tight">
                                        Delivered: {{$deliveredCount}}</figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{route('customer.index')}}">
                                <figure class="figure">
                                    <img src="{{asset('images/customers.png')}}"
                                         class="figure-img img-fluid rounded" style="width:250px;height:250px">
                                    <figcaption
                                        class="figure-caption font-semibold text-xl text-gray-800 leading-tight">
                                        Customers: {{$customersCount}}</figcaption>
                                </figure>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
</x-app-layout>
