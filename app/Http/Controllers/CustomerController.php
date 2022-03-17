<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\customer;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected $customer;

    public function __construct(CustomerService $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customer->all();

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->all();

        if (!$this->customer->save($data)) {
            return redirect()->back()->withErrors('There was a problem in adding customer');
        }

        return redirect()->route('customer.index')->with('message', "Customer successfully added");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = $this->customer->find($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest $request
     * @param  \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        if (!$this->customer->update($customer, $request->all())) {
            return redirect()->back()->withErrors('There was a problem in updating customer');
        }

        return redirect()->route('customer.index')->withSuccess("Customer successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (!$this->customer->delete($customer)) {
            return redirect()->back()->withErrors('There was a problem in deleting customer');
        }


        return redirect()->route('dashboard')->with('message', "Customer successfully deleted");

    }
}
