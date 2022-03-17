<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BillService;
use App\Services\CustomerService;
use Illuminate\Http\Request;

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
        $this->middleware(['auth', 'isAdmin']);
        $this->customer = $customer;
        $this->bill = $bill;
    }
    public function index() {
        $customersCount = $this->customer->total();
//        $unprocessedCount = $this->bill->getCount('unprocessed');
        $processingCount = $this->bill->getCount('processing');
        $completedCount = $this->bill->getCount('completed');
        $deliveredCount = $this->bill->getCount('delivered');
        return view('admin.dashboard', compact('customersCount', 'processingCount', 'completedCount', 'deliveredCount'));
    }
}
