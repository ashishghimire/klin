<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\customer;
use App\Services\CustomerService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

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
        $datatable = Datatables::of($customers)
            ->addIndexColumn()
            ->editColumn('created_at', function ($customer) {

                $date = date("Y-m-d", strtotime($customer->created_at));

                return $date;
            })
            ->addColumn('billing', function ($row) {

                $btn = '<a href=' . route('customer.bill.create', $row->id) . ' class="edit btn btn-primary btn-sm">Create Invoice</a>';

                return $btn;
            })
            ->addColumn('edit', function ($row) {

                $btn = '<a href=' . route('bill.edit', $row->id) . ' class="edit btn btn-secondary btn-sm">Edit Customer</a>';

                return $btn;
            })
            ->rawColumns(['billing', 'edit'])
            ->make(true);


        if (request()->ajax()) {
            return $datatable;
        }

        return view('customer.index');
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

        $customer = $this->customer->save($data);
        if (!$customer) {
            return redirect()->back()->withErrors('There was a problem in adding customer');
        }

        if (empty($data['billing'])) {
            return redirect()->route('customer.index')->with('message', "Customer successfully added");
        } else {
            return redirect()->route('customer.bill.create', $customer)->with('message', "Customer successfully added");
        }
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
