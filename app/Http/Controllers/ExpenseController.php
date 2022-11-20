<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMode;
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
        $today = Carbon::now();

        $sevenDaysBack = Carbon::now()->subDays(7);


        if (auth()->user()->role == 'admin') {
            $expenseQuery = Expense::whereDate('created_at', '>=', $sevenDaysBack->startOfDay()->toDateString())
                ->whereDate('created_at', '<=', $today->endOfDay()->toDateString())
                ->orderBy('created_at', 'desc');
        } else {
            $expenseQuery = Expense::whereDate('created_at', '>=', $sevenDaysBack->startOfDay()->toDateString())
                ->whereDate('created_at', '<=', $today->endOfDay()->toDateString())
                ->where('user_id', '=', auth()->user()->id)
                ->orderBy('created_at', 'desc');
        }

        extract($this->calculate($expenseQuery));

        request()->session()->put('expenses', $expenseQuery->with('user')->get());

        return view('expense.index', compact('expenses', 'total', 'electricity', 'detergent', 'rent', 'petrol', 'misc'));

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
            $expenseQuery->where(DB::raw('upper(category)'), strtoupper($category));
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
        $categories = auth()->user()->role == 'admin' ? ExpenseCategory::all() : ExpenseCategory::where(DB::raw('upper(name)'), '!=', 'SALARY')->where(DB::raw('upper(name)'), '!=', 'LUNCH')->where(DB::raw('upper(name)'), '!=', 'ALLOWANCE')->where(DB::raw('upper(name)'), '!=', 'CREDITED/ADJUSTED')->get();
        $employees = User::where('role', '!=', 'admin')->get();
        $modes = PaymentMode::all()->pluck('name', 'name');

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

            if (strtoupper($data['category']) == 'SALARY' || strtoupper($data['category']) == 'LUNCH' || strtoupper($data['category']) == 'ALLOWANCE' || strtoupper($data['category']) == 'CREDITED/ADJUSTED') {
                $salaryData['user_id'] = $data['employee_id'];
                $salaryData['expense_id'] = $expense->id;
                $salaryData['amount'] = $data['amount'];
                $salaryData['type'] = $data['category'];
                $salaryData['details'] = $data['details'];
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
        if (strtoupper($expense->category) == 'SALARY' || strtoupper($expense->category) == 'LUNCH' || strtoupper($expense->category) == 'ALLOWANCE' || strtoupper($expense->category) == 'CREDITED/ADJUSTED') {
            $categories = ExpenseCategory::where('name', $expense->category)->get();
            $employee = Salary::where('expense_id', $expense->id)->first()->user;
        } else {
            $categories = ExpenseCategory::where(DB::raw('upper(name)'), '!=', 'SALARY')->where(DB::raw('upper(name)'), '!=', 'LUNCH')->where(DB::raw('upper(name)'), '!=', 'ALLOWANCE')->where(DB::raw('upper(name)'), '!=', 'CREDITED/ADJUSTED')->get();
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

        if (!empty($data['nepali_date'])) {
            if ($data['nepali_date'] != $expense['nepali_date']) {
                $nepaliDate = $startDateNepali = explode("-", trim($data['nepali_date']));
                $englishDateArray = $this->nepaliDate->convertBsToAd(trim($nepaliDate[0]), trim($nepaliDate[1]), trim($nepaliDate[2]));
                $englishDate = implode("-", $englishDateArray);
                $createdAt = Carbon::parse($englishDate)->endOfDay();
                $expense->created_at = $createdAt;
                $expense->save();
            }
        }

        $expense->update($data);

        if (strtoupper($data['category']) == 'SALARY' || strtoupper($data['category']) == 'LUNCH' || strtoupper($data['category']) == 'ALLOWANCE' || strtoupper($data['category'] == 'CREDITED/ADJUSTED')) {
            $salary = $expense->salary;
            $salaryData['user_id'] = $data['employee_id'];
            $salaryData['expense_id'] = $expense->id;
            $salaryData['amount'] = $data['amount'];
            $salaryData['type'] = $data['category'];
            $salaryData['details'] = $data['details'];
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

    public function nepaliDate($englishDate)
    {
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateArray = $this->nepaliDate->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];
        return $nepaliDate;
    }
}
