<?php

namespace App\Http\Controllers;


use App\Imports\CustomerImport;
use App\Services\BillService;
use App\Services\CustomerService;
use Maatwebsite\Excel\Facades\Excel;


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
    public function __construct(CustomerService $customer, BillService $bill) {
        $this->middleware(['auth']);
        $this->middleware('isAdmin')->only(['import']);
        $this->customer = $customer;
        $this->bill = $bill;
    }
    public function index() {
        $customersCount = $this->customer->total();
        $unprocessedCount = $this->bill->getCount('unprocessed');
        $processingCount = $this->bill->getCount('processing');
        $completedCount = $this->bill->getCount('completed');
        $deliveredCount = $this->bill->getCount('delivered');
        return view('dashboard', compact('customersCount', 'processingCount', 'completedCount', 'deliveredCount', 'unprocessedCount'));
    }

    public function import()
    {
        $import = new CustomerImport;

        Excel::import($import, 'imports/customer_export.csv');
        dd($import->data);
    }
}
