<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecustomerRequest;
use App\Http\Requests\UpdatecustomerRequest;
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
     * @param  \App\Http\Requests\StorecustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecustomerRequest $request)
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
     * @param  \App\Http\Requests\UpdatecustomerRequest $request
     * @param  \App\Models\customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecustomerRequest $request, $id)
    {
        if (!$this->customer->update($id, $request->all())) {
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
    public function destroy($id)
    {
        if (!$this->customer->delete($id)) {
            return redirect()->back()->withErrors('There was a problem in deleting customer');
        }


        return redirect()->route('dashboard')->with('message', "Customer successfully deleted");

    }
}
