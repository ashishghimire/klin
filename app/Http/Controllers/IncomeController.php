<?php

namespace App\Http\Controllers;

use App\Exports\IncomeExport;
use App\Models\Bill;
use App\Services\BillService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Nilambar\NepaliDate\NepaliDate;

class IncomeController extends Controller
{
    /**
     * @var NepaliDate
     */
    protected $nepaliDate;

    /**
     * IncomeController constructor.
     * @param NepaliDate $nepaliDate
     */
    public function __construct(NepaliDate $nepaliDate)
    {

        $this->middleware('isAdmin');
        $this->nepaliDate = $nepaliDate;
    }

    public function index()
    {

        $today = Carbon::now()->startOfDay()->toDateString();

        $date = $this->todaysNepaliDate();

        $billsQuery = Bill::whereDate('created_at', $today);

        extract($this->calculate($billsQuery));

        return view('income.index', compact('income', 'vat', 'bills', 'date', 'total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid'));
    }

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


        $billsQuery = Bill::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);


        extract($this->calculate($billsQuery));


        return view('income.index', compact('income', 'vat', 'bills', 'date', 'total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid'));
    }


    public function calculate($billsQuery)
    {
        $total = 0;

        $cash = 0;

        $khalti = 0;

        $esewa = 0;

        $rewardPay = 0;

        $unpaid = 0;

        $income = 0;

        $vat = 0;

        foreach ($billsQuery->get() as $bill) {
            if ($bill->payment_mode != 'reward points') {
                $vat += ($bill->amount / 1.13) * 0.13;
                $total += $bill->amount;
                $income += $bill->amount / 1.13;
            }

            if ($bill->payment_mode == 'cash') {
                $cash += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'khalti') {
                $khalti += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'esewa') {
                $esewa += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'reward points') {
                $rewardPay += $bill->paid_amount;
            }

            $unpaid += $bill->amount - $bill->paid_amount;
        }

        $bills = $billsQuery->paginate(10);

        return compact('total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid', 'bills', 'vat', 'income');
    }

    public function fileExport()
    {
        return Excel::download(new IncomeExport, 'income_data.xlsx');
    }

    public function todaysNepaliDate()
    {
        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];
        return $nepaliDate;
    }

//-------------------------------------------------------------------


    public function index1()
    {

        $today = Carbon::now()->startOfDay()->toDateString();

        $date = $this->todaysNepaliDate();

        $select = DB::raw(
            'nepali_date,
            sum(amount) AS total, 
            sum(paid_amount) AS paid,
            sum(amount-paid_amount) AS unpaid,
            sum(case when payment_status = "unpaid" THEN amount else 0 end) AS unpaid,
            sum(case when payment_mode = "cash" THEN amount else 0 end) AS cash,
            sum(case when payment_mode = "khalti" THEN amount else 0 end) AS khalti,
            sum(case when payment_mode = "esewa" THEN amount else 0 end) AS esewa,
            sum(case when payment_mode = "reward points" THEN amount else 0 end) AS reward_pay');

        $billsQuery = DB::table('bills')
            ->whereDate('created_at', $today)
            ->where('deleted_at', null);

        extract($this->calculate1($billsQuery));

        $bills = $billsQuery
            ->select($select)
            ->groupBy('nepali_date')
            ->get();

        return view('income.index1', compact('bills', 'date', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid', 'total'));
    }


    public function search1()
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


        $select = DB::raw(
            'nepali_date,
            sum(amount) AS total,
            sum(paid_amount) AS paid,
            sum(amount-paid_amount) AS unpaid,
            sum(case when payment_mode = "cash" THEN amount else 0 end) AS cash,
            sum(case when payment_mode = "khalti" THEN amount else 0 end) AS khalti,
            sum(case when payment_mode = "esewa" THEN amount else 0 end) AS esewa,
            sum(case when payment_mode = "reward points" THEN amount else 0 end) AS reward_pay');

        $billsQuery = DB::table('bills')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('deleted_at', null);

        extract($this->calculate1($billsQuery));

        $bills = $billsQuery
            ->select($select)
            ->groupBy('nepali_date')
            ->get();

        return view('income.index1', compact('bills', 'date', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid', 'total'));

    }

    public function calculate1($billsQuery)
    {
        $total = 0;

        $cash = 0;

        $khalti = 0;

        $esewa = 0;

        $rewardPay = 0;

        $unpaid = 0;

        $income = 0;

        $vat = 0;

        $data = [];

        foreach ($billsQuery->get() as $bill) {


            if ($bill->payment_mode != 'reward points') {
                $total += $bill->amount;
                $income += $bill->amount / 1.13;
            }

            if ($bill->payment_mode == 'cash') {
                $cash += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'khalti') {
                $khalti += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'esewa') {
                $esewa += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'reward points') {
                $rewardPay += $bill->paid_amount;
            }

            $unpaid += $bill->amount - $bill->paid_amount;

        }
        $bills = $billsQuery->paginate(10);

        return compact('total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid', 'bills', 'vat', 'income', 'data');
    }

}
