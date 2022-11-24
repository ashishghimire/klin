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

        $this->middleware('auth');
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
        if (request()->session()->has('bills')) {
            if (!request()->session()->get('bills')->isEmpty()) {
                return Excel::download(new IncomeExport, 'income_data.xlsx');
            } else {
                return redirect()->route('income1')->with('error', 'Error!! Please refresh the page and try again, or contact admin');
            }
        } else {
            return redirect()->route('income1')->with('error', 'Error!! Please refresh the page and try again, or contact admin');
        }
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

        $sevenDaysBack = Carbon::now()->subDays(7)->startOfDay()->toDateString();

        $date = $this->todaysNepaliDate();

        return $this->queryDate($sevenDaysBack, $today, $date);
    }


    public function search1()
    {
        $startDate = null;
        $endDate = null;

        if (!empty(trim(request()->get('startDate')))) {
            $startDateNepali = explode("-", trim(request()->get('startDate')));
            $startDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]));
            $startDateEnglish = implode("-", $startDateEnglishArray);
            $startDate = Carbon::parse(strtotime($startDateEnglish))->startOfDay()->toDateString();
        }

        if (!empty(trim(request()->get('endDate')))) {
            $endDateNepali = explode("-", trim(request()->get('endDate')));
            $endDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]));
            $endDateEnglish = implode("-", $endDateEnglishArray);
            $endDate = Carbon::parse(strtotime($endDateEnglish))->endOfDay()->toDateString();
        }


//        $date = request()->get('startDate') . ' : ' . request()->get('endDate');


        return $this->queryDate($startDate, $endDate);
    }

    public function calculate1($billsQuery)
    {
        $total = 0;

        $cash = 0;

        $rewardPay = 0;

        $unpaid = 0;

        $fonepay = 0;


        foreach ($billsQuery->get() as $bill) {


            if ($bill->payment_mode != 'reward points') {
                $total += $bill->amount;
            }

            if ($bill->payment_mode == 'cash') {
                $cash += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'khalti' || $bill->payment_mode == 'esewa' || $bill->payment_mode == 'fonepay') {
                $fonepay += $bill->paid_amount;
            }

            if ($bill->payment_mode == 'reward points') {
                $rewardPay += $bill->paid_amount;
            }

            $unpaid += $bill->amount - $bill->paid_amount;

        }


        $total = round($total - $unpaid, 2);
        $cash = round($cash, 2);
        $rewardPay = round($rewardPay, 2);
        $fonepay = round($fonepay, 2);
        $unpaid = round($unpaid, 2);

        return compact('total', 'cash', 'rewardPay', 'unpaid', 'fonepay');
    }

    private function queryDate($startDate, $endDate)
    {
        $select = DB::raw(
            'nepali_date,
            sum(amount) AS total,
            sum(paid_amount) AS paid,
            sum(amount-paid_amount) AS unpaid,
            sum(case when payment_mode = "cash" THEN paid_amount else 0 end) AS cash,
            sum(case when payment_mode = "khalti" or payment_mode = "esewa" or payment_mode = "fonepay" THEN paid_amount else 0 end) AS fonepay,
            sum(case when payment_mode = "reward points" THEN paid_amount else 0 end) AS reward_pay');


        $billsQuery = DB::table('bills')
            ->where('deleted_at', null);

        if (!empty($startDate)) {
            $billsQuery->whereDate('created_at', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $billsQuery->whereDate('created_at', '<=', $endDate);
        }

        extract($this->calculate1($billsQuery));

        $bills = $billsQuery
            ->select($select)
            ->groupBy('nepali_date')
            ->get();

        request()->session()->put('bills', $bills);

        return view('income.index1', compact('bills', 'cash', 'rewardPay', 'unpaid', 'total', 'fonepay'));
    }

}
