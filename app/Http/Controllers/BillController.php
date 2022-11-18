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
        $this->middleware('isAdmin',['only' => ['edit', 'update', 'destroy']]);
        $this->nepaliDate = $nepaliDate;
    }

    public function index()
    {
        $nepaliDateObj = $this->nepaliDate;
        $laundryStatus = request()->query('laundry-status');
        $number = 10000;

        if (empty($laundryStatus)) {

            $bills = $this->bill->get($number);
        }

        else
        {
            $bills = Bill::where('laundry_status', $laundryStatus)->orderBy('created_at', 'desc')->take($number)->get();
        }

        $paymentModes = PaymentMode::where('name', '!=', 'reward points');

        return view('bill.index', compact('bills', 'paymentModes', 'nepaliDateObj'));
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
        if (!$this->bill->update($bill, $request->all())) {
            return redirect()->back()->withErrors('There was a problem in updating bill');
        }

        return redirect()->route('bill.index')->withSuccess("Bill successfully updated");
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
            $bill->save();
        }

        return redirect()->route('bill.index');
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
        $startDateNepali = explode("-", trim(request()->get('startDate')));
        $endDateNepali = explode("-", trim(request()->get('endDate')));

        $startDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]));

        $endDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]));

        $startDateEnglish = implode("-", $startDateEnglishArray);
        $endDateEnglish = implode("-", $endDateEnglishArray);

        $date = request()->get('startDate') . ' : ' . request()->get('endDate');

        $startDate = Carbon::parse(strtotime($startDateEnglish))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($endDateEnglish))->endOfDay()->toDateString();

        return $this->queryDate($startDate, $endDate, $date);
    }

    private function queryDate($startDate, $endDate, $date)
    {
        $bills = Bill::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('deleted_at', null)->get();

        $nepaliDateObj = $this->nepaliDate;

        $paymentModes = PaymentMode::all();

        return view('bill.index', compact('bills', 'nepaliDateObj', 'paymentModes'));
    }

}
