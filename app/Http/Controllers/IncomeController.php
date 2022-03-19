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
        $today = Carbon::now()->toDateString();
//        $bills = Bill::paginate(2);

        $bills = Bill::whereDate('created_at', $today)->paginate(1);



        return view('income.index', compact('bills', 'today'));
    }
}
