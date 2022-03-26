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
        $date = Carbon::now()->format('m/d/Y');
//
        $bills = Bill::whereDate('created_at', $date)->paginate(10);


        return view('income.index', compact('bills', 'date'));
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

        foreach ($billsQuery->get() as $bill) {
            if ($bill->payment_mode != 'reward points')
                $total += $bill->amount;
        }


        $bills = $billsQuery->paginate(10);
        return view('income.index', compact('bills', 'date', 'total'));
    }
}
