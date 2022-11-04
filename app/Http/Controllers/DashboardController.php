<?php

namespace App\Http\Controllers;


use App\Imports\CustomerImport;
use App\Imports\ExpenseImport;
use App\Imports\LaundryImport;
use App\Models\Customer;
use App\Models\ImportedCustomer;
use App\Models\ImportedLaundry;
use App\Models\Setting;
use App\Services\BillService;
use App\Services\CustomerService;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Nilambar\NepaliDate\NepaliDate;


class DashboardController extends Controller
{
    /**
     * @var CustomerService
     */
    protected $customer;
    /**
     * @var BillService
     */
    protected $bill;

    /**
     * DashboardController constructor.
     * @param CustomerService $customer
     * @param BillService $bill
     */
    public function __construct(CustomerService $customer, BillService $bill)
    {
        $this->middleware(['auth']);
        $this->middleware('isAdmin')->only(['import']);
        $this->customer = $customer;
        $this->bill = $bill;
    }

    public function index()
    {
        $customersCount = $this->customer->total();
        $unprocessedCount = $this->bill->getCount('unprocessed');
        $processingCount = $this->bill->getCount('processing');
        $completedCount = $this->bill->getCount('completed');
        $deliveredCount = $this->bill->getCount('delivered');
        return view('dashboard', compact('customersCount', 'processingCount', 'completedCount', 'deliveredCount', 'unprocessedCount'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import()
    {

        $nepaliDate = new NepaliDate;
        Excel::import(new ExpenseImport($nepaliDate), 'imports/expense_export.csv');
        Excel::import(new CustomerImport, 'imports/customer_export.csv');
        Excel::import(new LaundryImport, 'imports/laundry_export.csv');


        $importedLaundries = ImportedLaundry::all();

        $rewardKey = Setting::first()->rewards_key;

        foreach ($importedLaundries as $importedLaundry) {

            $importedCustomer = ImportedCustomer::where('manual_id', $importedLaundry->imported_customer_manual_id)->first();

            if (!empty($importedCustomer)) {
                $rewardPoints = floatval($importedLaundry->amount) * floatval($rewardKey);
                $importedCustomer->reward_points += floatval($rewardPoints);
                $importedCustomer->save();
            }
        }

        $importedCustomers = ImportedCustomer::all('name', 'address', 'phone', 'reward_points', 'created_at', 'updated_at', 'nepali_date');


        Customer::insert($importedCustomers->toArray());

        return redirect()->route('dashboard');

    }
}
