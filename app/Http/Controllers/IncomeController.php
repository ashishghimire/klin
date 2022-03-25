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
        $date = Carbon::now()->toDateString();
//
        $bills = Bill::whereDate('created_at', $date)->paginate(1);


        return view('income.index', compact('bills', 'date'));
    }

    public function search()
    {
        $date = request()->get('datefilter');

        $dates = explode("-", $date);

        $startDate = Carbon::parse(strtotime($dates[0]))->startOfDay();

        $endDate = Carbon::parse(strtotime($dates[0]))->endOfDay();

        $bills = Bill::whereBetween('created_at', [$startDate, $endDate])->paginate(1);

        return view('income.index', compact('bills', 'date'));
    }
}
