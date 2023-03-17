<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use App\Models\PaymentMode;
use App\Models\Service;
use App\Services\BillService;
use App\Services\CustomerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Nilambar\NepaliDate\NepaliDate;
use Yajra\DataTables\Facades\DataTables;


class BillController extends Controller
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
     * @var NepaliDate
     */
    protected $nepaliDate;

    /**
     * Display a listing of the resource.
     *
     * @param CustomerService $customer
     * @param BillService $bill
     * @param NepaliDate $nepaliDate
     */

    public function __construct(CustomerService $customer, BillService $bill, NepaliDate $nepaliDate)
    {
        $this->customer = $customer;
        $this->bill = $bill;
        $this->middleware('auth');
        $this->middleware('isAdmin', ['only' => ['edit', 'update', 'destroy']]);
        $this->nepaliDate = $nepaliDate;
    }

    public function index()
    {
        $isSearch = false;
        $laundryStatus = request()->query('laundry-status');


        return view('bill.load', compact('laundryStatus', 'isSearch'));
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
     * @param $customerId
     * @param  \App\Http\Requests\StoreBillRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerId, StoreBillRequest $request)
    {
        $data = $request->all();

        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];

        $data['nepali_date'] = $nepaliDate;

        $customer = $this->customer->find($customerId);

        if ($request->payment_mode == 'reward points') {
            $amount = $this->bill->calculateAmount($data['service_details']);
            if ($customer->reward_points < $amount) {
                return redirect()->back()->withErrors('There aren\'t enough reward points to pay for this bill');
            }
        }

        $bill = $this->bill->save($customerId, $data);

        if (!$bill) {
            return redirect()->back()->withErrors('There was a problem in creating bill');
        }


        return redirect()->route('customer.bill.show', [$customerId, $bill]);
    }

    /**
     * Display the specified resource.
     *
     * @param $customerId
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function show($customerId, Bill $bill)
    {
        return view('bill.show', compact('customerId', 'bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        $services = Service::all();

        $paymentModes = PaymentMode::all();

        return view('bill.edit', compact('bill', 'services', 'paymentModes'));
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
        $data = $request->all();

        if (!empty($data['nepali_date'])) {
            if ($data['nepali_date'] != $bill['nepali_date']) {
                $nepaliDate = explode("-", trim($data['nepali_date']));
                $englishDateArray = $this->nepaliDate->convertBsToAd(trim($nepaliDate[0]), trim($nepaliDate[1]), trim($nepaliDate[2]));
                $englishDate = implode("-", $englishDateArray);
                $createdAt = Carbon::parse($englishDate)->endOfDay();
                $bill->created_at = $createdAt;
                $bill->save();
            }
        }

        $updatedBill = $this->bill->update($bill, $data);


        if (!$updatedBill) {
            return redirect()->back()->withErrors('There was a problem in updating bill');
        }


        return redirect()->route('bill.index', ['laundry-status' => 'processing'])->withSuccess("Bill successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        if (!$this->bill->delete($bill)) {
            return redirect()->back()->withErrors('There was a problem in deleting Bill');
        }

        return redirect()->route('dashboard')->with('message', "Bill successfully deleted");
    }

    public function createInvoice()
    {
        $customers = $this->customer->all();

        return view('bill.invoice', compact('customers'));
    }

    public function changePaymentStatus(Bill $bill)
    {
        if (!empty(request()->payment_mode)) {
            $bill->paid_amount = $bill->amount;
            $bill->payment_status = 'paid';
            $bill->payment_mode = request()->payment_mode;
            $englishDate = Carbon::now();
            $bill->created_at = $englishDate;
            $bill->nepali_date = $this->nepaliDate($englishDate);
            $bill->save();
        }

        return redirect()->back();
    }

    public function changeLaundryStatus(Bill $bill)
    {
        if (request()->ajax()) {
            $bill->laundry_status = request()->laundry_status;
            $bill->save();
            return true;
        }
    }

//--------------------------------------------------------
    public function search()
    {
        $isSearch = true;
        $startDateNepali = explode("-", trim(request()->get('startDate')));

        if (empty($this->nepaliDate->validateDate(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]), 'bs'))) {
            return redirect()->back()->withErrors('Invalid Start Date Entered: ' . trim(request()->get('startDate')));
        }

        $endDateNepali = explode("-", trim(request()->get('endDate')));

        if (empty($this->nepaliDate->validateDate(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]), 'bs'))) {
            return redirect()->back()->withErrors('Invalid End Date Entered: ' . trim(request()->get('endDate')));
        }

        $startDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]));

        $endDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]));

        $startDateEnglish = implode("-", $startDateEnglishArray);
        $endDateEnglish = implode("-", $endDateEnglishArray);

        $startDate = Carbon::parse(strtotime($startDateEnglish))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($endDateEnglish))->endOfDay()->toDateString();

        $laundryStatus = request()->get('laundry-status');

        return view('bill.load', compact('startDate', 'endDate', 'laundryStatus', 'isSearch'));
    }

    private function queryDate($startDate, $endDate, $status)
    {
        if (!empty($status)) {
            $bills = DB::table('bills')
                ->where('laundry_status', $status)
                ->whereDate('bills.created_at', '>=', $startDate)
                ->whereDate('bills.created_at', '<=', $endDate)
                ->where('bills.deleted_at', null)
                ->join('customers', 'bills.customer_id', '=', 'customers.id')
                ->join('users', 'bills.user_id', '=', 'users.id')
                ->select('bills.*', 'customers.id as customer_id', 'customers.phone as customer_phone', 'customers.name as customer_name', 'users.id as user_id', 'users.name as user_name');

        } else {
            $bills = DB::table('bills')
                ->whereDate('bills.created_at', '>=', $startDate)
                ->whereDate('bills.created_at', '<=', $endDate)
                ->where('bills.deleted_at', null)
                ->join('customers', 'bills.customer_id', '=', 'customers.id')
                ->join('users', 'bills.user_id', '=', 'users.id')
                ->select('bills.*', 'customers.id as customer_id', 'customers.phone as customer_phone', 'customers.name as customer_name', 'users.id as user_id', 'users.name as user_name');
        }


        return $bills;
    }

    public function nepaliDate($englishDate)
    {
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];

        return $nepaliDate;
    }


    public function loadIndex()
    {

        $laundryStatus = request()->query('laundryStatus');

        if (empty($laundryStatus)) {

            $bills = DB::table('bills')
                ->where('bills.deleted_at', null)
                ->join('customers', 'bills.customer_id', '=', 'customers.id')
                ->join('users', 'bills.user_id', '=', 'users.id')
                ->select('bills.*', 'customers.id as customer_id', 'customers.phone as customer_phone', 'customers.name as customer_name', 'users.id as user_id', 'users.name as user_name');


        } else {

//
//            $bills = DB::table('bills')
//                ->where('laundry_status', 'completed')
//                ->join('customers', 'bills.customer_id', '=', 'customers.id')
//                ->join('users', 'bills.user_id', '=', 'users.id')
//                ->join('services', DB::raw('JSON_CONTAINS(bills.service_details, JSON_OBJECT("service_type", services.name))'), '=', DB::raw('1'))
//                ->select('bills.*', 'customers.phone as customer_phone', 'customers.name as customer_name', 'users.name as user_name', DB::raw('GROUP_CONCAT(services.shortcode SEPARATOR ", ") AS laundry_service'))
//                ->groupBy('bills.id');

            $bills = DB::table('bills')
                ->where('bills.deleted_at', null)
                ->where('laundry_status', $laundryStatus)
                ->join('customers', 'bills.customer_id', '=', 'customers.id')
                ->join('users', 'bills.user_id', '=', 'users.id')
                ->select('bills.*', 'customers.id as customer_id', 'customers.phone as customer_phone', 'customers.name as customer_name', 'users.id as user_id', 'users.name as user_name');
        }

        return $this->load($bills);
    }

    public function loadSearch()
    {

        $laundryStatus = request()->query('laundryStatus');
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');
        $bills = $this->queryDate($startDate, $endDate, $laundryStatus);

        return $this->load($bills);
    }

    public function load($bills)
    {
        $billDatatable = Datatables::of($bills)
            ->addIndexColumn()
            ->addColumn('estimate_no', function ($bill) {
                $id = '<a href=' . route('customer.bill.show', [$bill->customer_id, $bill->id]) . '>' . $bill->id . '</a>';

                return $id;
            })
            ->addColumn('customer_name', function ($bill) {

                $customerName = '<a href=' . route('customer.show', [$bill->customer_id]) . '>' . $bill->customer_name . '</a>';

                return $customerName;
            })
            ->addColumn('phone_no', function ($bill) {

                $phoneNo = '<a href=' . route('customer.show', [$bill->customer_id]) . '>' . $bill->customer_phone . '</a>';

                return $phoneNo;
            })
            ->addColumn('services', function ($bill) {
                $shortcodes = [];
                $service_details = json_decode($bill->service_details);
                foreach ($service_details as $service_detail) {
                    $servicesShortcode = \App\Models\Service::where('name', $service_detail->service_type)->first('shortcode');
                    if (!empty ($servicesShortcode)) {
                        array_push($shortcodes, $servicesShortcode->shortcode);
                    }
                }

                return implode($shortcodes, ', ');
                return $bill->laundry_service;
            })
            ->addColumn('amount', function ($bill) {
                return $bill->amount;
            })
            ->addColumn('payment_status', function ($bill) {

                $paymentModes = PaymentMode::where('name', '!=', 'reward points');

                return view('bill.partials.payment-status', compact('bill', 'paymentModes'))->render();

            })
            ->addColumn('payment_mode', function ($bill) {
                $imagePath = asset('images/payment_modes/money.png');
                if (!empty($bill->payment_mode)) {
                    if (file_exists(config('public.path') . '/images/payment_modes/' . $bill->payment_mode . '.png')) { //base_path() if in production
                        $imagePath = asset('images/payment_modes/' . $bill->payment_mode . '.png');
                    }
                } else {
                    $imagePath = asset('images/payment_modes/unpaid.png');
                }

                $paymentMode = empty($bill->payment_mode) ? '-' : $bill->payment_mode;

                return $paymentMode . '<img src="' . $imagePath . '" style="width:20px;float:left; margin-right: 10%">';

            })
            ->addColumn('laundry_status', function ($bill) {
                if ($bill->payment_status == 'paid') {
                    $laundryStatusArray = ['processing' => 'Processing', 'completed' => 'Completed', 'delivered' => 'Delivered'];
                } else {
                    $laundryStatusArray = ['processing' => 'Processing', 'completed' => 'Completed'];
                }
                return view('bill.partials.laundry-status-form', compact('bill', 'laundryStatusArray'))->render();
            })
            ->addColumn('date', function ($bill) {
                return !empty($bill->nepali_date) ? $bill->nepali_date : '';
            })
            ->addColumn('note', function ($bill) {
                return !empty($bill->note) ? $bill->note : '';
            })->addColumn('added_by', function ($bill) {
                $addedBy = '<a href=' . route('employee.show', $bill->user_id) . '>' . $bill->user_name . '</a>';
                return $addedBy;
            })
            ->addColumn('action', function ($bill) {
                $action = '<a class="btn btn-outline-dark" href=' . route('bill.edit', $bill->id) . '>Edit </a>';
                return $action;
            })
            ->rawColumns(['estimate_no', 'customer_name', 'phone_no', 'payment_status', 'payment_mode', 'laundry_status', 'added_by', 'action'])
            ->toJson();

        return $billDatatable;
    }
}
