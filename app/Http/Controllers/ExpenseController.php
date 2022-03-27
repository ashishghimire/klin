<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * @var Expense
     */
    protected $expense;

    /**
     * ExpenseController constructor.
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {

        $this->expense = $expense;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now()->startOfDay()->toDateString();

        $date = Carbon::now()->format('m/d/Y');

        $expenseQuery = Expense::whereDate('created_at', $today);

        $total = 0;


        foreach ($expenseQuery->get() as $expense) {
            $total += $expense->amount;
        }

        $expenses = $expenseQuery->paginate(10);

        return view('expense.index', compact('expenses', 'total', 'date'));

    }

    public function search()
    {
        $date = request()->get('datefilter');

        $dates = explode("-", $date);

        $startDate = Carbon::parse(strtotime($dates[0]))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($dates[1]))->endOfDay()->toDateString();

        $expenseQuery = Expense::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $total = 0;



        foreach ($expenseQuery->get() as $expense) {
                $total += $expense->amount;
        }

        $expenses = $expenseQuery->paginate(10);

        return view('expense.index', compact('expenses', 'total', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseCategory::all();

        return view('expense.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        DB::beginTransaction();

        try {
            $this->expense->create($data);

            DB::commit();
            return redirect()->route('expense.index');

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return redirect()->back()->withErrors('There was a problem in creating expense');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
