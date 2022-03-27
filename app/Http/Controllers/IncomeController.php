<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Services\BillService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncomeController extends Controller
{


    public function index()
    {

        $today = Carbon::now()->startOfDay()->toDateString();

        $date = Carbon::now()->format('m/d/Y');

        $billsQuery = Bill::whereDate('created_at', $today);

        $total = 0;

        $cash = 0;

        $khalti = 0;

        $esewa = 0;

        $rewardPay = 0;

        $unpaid = 0;

        $vat = 0;

        $income = 0;

        foreach ($billsQuery->get() as $bill) {
            if ($bill->payment_mode != 'reward points') {
                $vat += $bill->amount*0.13;
                $total += $bill->amount;
                $income += $total-$vat;
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

        return view('income.index', compact('income', 'vat', 'bills', 'date', 'total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid'));
    }

    public function search()
    {
        $date = request()->get('datefilter');

        $dates = explode("-", $date);

        $startDate = Carbon::parse(strtotime($dates[0]))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($dates[1]))->endOfDay()->toDateString();

        $billsQuery = Bill::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

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
                $vat += ($bill->amount/1.13)*0.13;
                $total += $bill->amount;
                $income += $bill->amount/1.13;
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

        return view('income.index', compact('income', 'vat','bills', 'date', 'total', 'cash', 'khalti', 'esewa', 'rewardPay', 'unpaid'));
    }
}
