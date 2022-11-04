<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Nilambar\NepaliDate\NepaliDate;

class ExpenseController extends Controller
{
    /**
     * @var Expense
     */
    protected $expense;
    /**
     * @var NepaliDate
     */
    protected $nepaliDate;
    /**
     * @var Salary
     */
    protected $salary;

    /**
     * ExpenseController constructor.
     * @param Expense $expense
     * @param NepaliDate $nepaliDate
     * @param Salary $salary
     */
    public function __construct(Expense $expense, NepaliDate $nepaliDate, Salary $salary)
    {

        $this->expense = $expense;
        $this->middleware('auth');
        $this->middleware('isAdmin', ['only' => ['search', 'fileExport', 'edit', 'destroy']]);

        $this->nepaliDate = $nepaliDate;
        $this->salary = $salary;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now()->startOfDay()->toDateString();

        $date = $this->todaysNepaliDate();

        if (auth()->user()->role == 'admin') {
            $expenseQuery = Expense::whereDate('created_at', $today);
        } else {
            $expenseQuery = Expense::whereDate('created_at', $today)
                ->where('user_id', '=', auth()->user()->id);
        }

        extract($this->calculate($expenseQuery));

        request()->session()->put('expenses', $expenseQuery->with('user')->get());

        return view('expense.index', compact('expenses', 'total', 'date', 'electricity', 'detergent', 'rent', 'petrol', 'misc'));

    }

    public function calculate($expenseQuery)
    {
        $total = 0;

        $electricity = 0;

        $detergent = 0;

        $rent = 0;

        $petrol = 0;

        $misc = 0;


        foreach ($expenseQuery->get() as $expense) {
            $total += $expense->amount;

            if ($expense->category == 'electricity') {
                $electricity += $expense->amount;
            }

            if ($expense->category == 'detergent') {
                $detergent += $expense->amount;
            }

            if ($expense->category == 'rent') {
                $rent += $expense->amount;
            }

            if ($expense->category == 'petrol') {
                $petrol += $expense->amount;
            }

            if ($expense->category == 'misc') {
                $misc += $expense->amount;
            }
        }

        $expenses = $expenseQuery->paginate(10);

        return compact('total', 'electricity', 'detergent', 'rent', 'petrol', 'misc', 'expenses');

    }

    public function search()
    {
        $category = request()->get('category');

        $startDateNepali = explode("-", trim(request()->get('startDate')));
        $endDateNepali = explode("-", trim(request()->get('endDate')));

        $startDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]));

        $endDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]));

        $startDateEnglish = implode("-", $startDateEnglishArray);
        $endDateEnglish = implode("-", $endDateEnglishArray);

        $date = request()->get('startDate') . ' : ' . request()->get('endDate');

        $startDate = Carbon::parse(strtotime($startDateEnglish))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($endDateEnglish))->endOfDay()->toDateString();

        if (auth()->user()->role == 'admin') {
            $expenseQuery = Expense::whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        } else {
            $expenseQuery = Expense::whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->where('user_id', '=', auth()->user()->id);
        }

        if (!empty($category)) {
            $expenseQuery->where('category', $category);
        }

        extract($this->calculate($expenseQuery));

        request()->session()->put('expenses', $expenseQuery->with('user')->get());


        return view('expense.index', compact('expenses', 'total', 'date', 'electricity', 'detergent', 'rent', 'petrol', 'misc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = auth()->user()->role == 'admin' ? ExpenseCategory::all() : ExpenseCategory::where('name', '!=', 'salary')->get();
        $employees = User::where('role', '!=', 'admin')->get();
        $modes = ['cash' => 'Cash', 'cheque' => 'Cheque', 'bank' => 'Bank Deposit', 'other' => 'Other'];

        return view('expense.create', compact('categories', 'employees', 'modes'));
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

        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];

        $data['nepali_date'] = $nepaliDate;
        $salaryData = [];

        DB::beginTransaction();

        try {
            $expense = $this->expense->create($data);

            if ($data['category'] == 'salary') {
                $salaryData['user_id'] = $data['employee_id'];
                $salaryData['expense_id'] = $expense->id;
                $salaryData['amount'] = $data['amount'];
                $this->salary->create($salaryData);
            }

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
        $employee = null;
        if ($expense->category == 'salary') {
            $categories = ExpenseCategory::where('name', 'salary')->get();
            $employee = Salary::where('expense_id', $expense->id)->first()->user;
        } else {
            $categories = ExpenseCategory::where('name', '!=', 'salary')->get();
        }

        $employees = User::where('role', '!=', 'admin')->get();

        $modes = ['cash' => 'Cash', 'cheque' => 'Cheque', 'bank' => 'Bank Deposit', 'other' => 'Other'];

        return view('expense.edit', compact('categories', 'employees', 'expense', 'employee', 'modes'));
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
        $data = $request->all();

        $expense->update($data);

        if ($data['category'] == 'salary') {
            $salary = $expense->salary;
            $salaryData['user_id'] = $data['employee_id'];
            $salaryData['expense_id'] = $expense->id;
            $salaryData['amount'] = $data['amount'];
            $salary->update($salaryData);
        }

        return redirect()->route('expense.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expense.index');
    }

    public function fileExport()
    {

        if (request()->session()->has('expenses')) {
            if (!request()->session()->get('expenses')->isEmpty()) {
                return Excel::download(new ExpenseExport, 'expense_data.xlsx');
            } else {
                return redirect()->route('expense.index')->with('error', 'Error!! Please refresh the page and try again, or contact admin');
            }
        } else {
            return redirect()->route('expense.index')->with('error', 'Error!! Please refresh the page and try again, or contact admin');
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
}
