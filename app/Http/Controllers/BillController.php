<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use App\Models\PaymentMode;
use App\Models\Service;
use App\Services\CustomerService;

class BillController extends Controller
{
    /**
     * @var CustomerService
     */
    protected $customer;

    /**
     * Display a listing of the resource.
     *
     * @param CustomerService $customer
     */

    public function __construct(CustomerService $customer)
    {

        $this->customer = $customer;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function create($customerId)
    {
        $customer = $this->customer->find($customerId);

        $services = Service::all();

        $paymentModes = PaymentMode::all();

        return view('bill.create', compact('customer', 'services', 'paymentModes'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBillRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBillRequest $request)
    {
        dd($request->all());
        dd(json_encode($request->service_details));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBillRequest $request
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
