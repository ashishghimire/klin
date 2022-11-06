<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\customer;
use App\Models\Setting;
use App\Services\CustomerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Nilambar\NepaliDate\NepaliDate;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected $customer;
    /**
     * @var NepaliDate
     */
    protected $nepaliDate;

    /**
     * CustomerController constructor.
     * @param CustomerService $customer
     * @param NepaliDate $nepaliDate
     */
    public function __construct(CustomerService $customer, NepaliDate $nepaliDate)
    {
        $this->customer = $customer;
        $this->middleware('auth');
        $this->middleware('isAdmin', ['only' => ['edit', 'update', 'destroy', 'editRewardKey', 'updateRewardKey', 'fileExport']]);
        $this->nepaliDate = $nepaliDate;
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
            ->editColumn('name', function ($customer) {
                $name = '<a href=' . route('customer.show', $customer->id) . '>' . $customer->name . '</a>';

                return $name;
            })
            ->editColumn('created_at', function ($customer) {

                $date = $customer->nepali_date;

                return $date;
            })
            ->addColumn('billing', function ($row) {

                $btn = '<a href=' . route('customer.bill.create', $row->id) . ' class="edit btn btn-link btn-sm">Create Invoice</a>';

                return $btn;
            })
            ->addColumn('edit', function ($row) {

                $btn = '<a href=' . route('customer.edit', $row->id) . ' class="edit btn btn-link btn-sm">Edit Customer</a>';

                return $btn;
            })
            ->rawColumns(['name', 'billing', 'edit'])
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

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];

        $data['nepali_date'] = $nepaliDate;

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
        $customer = $this->customer->find($id);

        return view('customer.show', compact('customer'));
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
        $data = $request->all();
        if (!empty($data['new-password'])) {
            $data['password'] = bcrypt($data['new-password']);

        }

        if (!$this->customer->update($customer, $data)) {
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

    public function editRewardKey()
    {
        $setting = Setting::first();

        return view('customer.rewards', compact('setting'));
    }

    public function updateRewardKey(Request $request)
    {
        $setting = Setting::first();

        $validator = Validator::make($request->all(), [
            'rewards_key' => 'numeric|min:0|max:1'
        ]);

        if ($validator->fails()) {
            return redirect()->route('rewards.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $setting->update($request->all());

        return redirect()->route('dashboard')->with('message', "Reward Key successfully set to " . $request->rewards_key);
    }


    public function fileExport()
    {
        return Excel::download(new CustomersExport, 'customer_data.xlsx');
    }
}
