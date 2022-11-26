<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nilambar\NepaliDate\NepaliDate;

class EmployeeController extends Controller
{
    /**
     * @var NepaliDate
     */
    protected $nepaliDate;

    /**
     * EmployeeController constructor.
     * @param NepaliDate $nepaliDate
     */
    public function __construct(NepaliDate $nepaliDate)
    {
        $this->middleware('auth');
        $this->middleware('isAdmin')->except('show');
        $this->nepaliDate = $nepaliDate;
    }

    public function index()
    {
        $employees = User::where('role', '!=', 'admin')->get();

        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        return view('employee.create');
    }

    public function edit(User $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        User::create($data);

        return redirect()->route('employee.index')->with('message', "Employee Successfully Created");
    }

    public function update(User $employee)
    {
        Validator::make(request()->all(), [
            'username' => [
                'required', 'string',
                Rule::unique('users')->ignore($employee)
            ],
            'name' => ['required', 'string'],
        ]);

        $data = request()->all();
        if (!empty($data['new-password'])) {
            $data['password'] = bcrypt($data['new-password']);

        }

        $employee->update($data);

        return redirect()->route('employee.index')->with('message', "Employee Successfully Updated");
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('message', "Employee Successfully Deleted");

    }

    public function show(User $employee)
    {
        if (!(auth()->user()->role == 'admin' || auth()->user()->id == $employee->id))
            return redirect()->route('employee.index')->with('message', "You are not allowed to view this page");

//        $select = DB::raw(
//            '*,
//            sum(case when upper(type) = "LUNCH" THEN amount else 0 end) AS total_lunch,
//            sum(case when upper(type) = "SALARY" THEN amount else 0 end) AS total_salary,
//            sum(case when upper(type) = "ALLOWANCE" THEN amount else 0 end) AS total_allowance,
//            sum(case when upper(type) = "CREDITED/ADJUSTED" THEN amount else 0 end) AS total_credited');

        $salaries = $employee->salaries()->get();

        extract($this->calculate($salaries));

        return view('employee.show', compact('employee', 'salaries', 'totalSalary', 'totalLunch', 'totalCredited'));
    }

    public function search(User $employee)
    {
        if (!(auth()->user()->role == 'admin' || auth()->user()->id == $employee->id))
            return redirect()->route('employee.index')->with('message', "You are not allowed to view this page");

        $startDateNepali = explode("-", trim(request()->get('startDate')));
        $endDateNepali = explode("-", trim(request()->get('endDate')));

        $startDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($startDateNepali[0]), trim($startDateNepali[1]), trim($startDateNepali[2]));

        $endDateEnglishArray = $this->nepaliDate->convertBsToAd(trim($endDateNepali[0]), trim($endDateNepali[1]), trim($endDateNepali[2]));

        $startDateEnglish = implode("-", $startDateEnglishArray);
        $endDateEnglish = implode("-", $endDateEnglishArray);

        $startDate = Carbon::parse(strtotime($startDateEnglish))->startOfDay()->toDateString();

        $endDate = Carbon::parse(strtotime($endDateEnglish))->endOfDay()->toDateString();

//        $select = DB::raw(
//            '*,
//            sum(case when upper(type) = "LUNCH" THEN amount else 0 end) AS total_lunch,
//            sum(case when upper(type) = "SALARY" THEN amount else 0 end) AS total_salary,
//            sum(case when upper(type) = "ALLOWANCE" THEN amount else 0 end) AS total_allowance,
//            sum(case when upper(type) = "CREDITED/ADJUSTED" THEN amount else 0 end) AS total_credited');

        $salaries = $employee->salaries()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        extract($this->calculate($salaries));

        return view('employee.show', compact('employee', 'salaries', 'totalSalary', 'totalLunch', 'totalCredited'));
    }

    protected function calculate($salaries)
    {
        $totalSalary = 0;
        $totalLunch = 0;
        $totalCredited = 0;

        foreach (($salaries) as $salary) {


            if (strtoupper($salary->type) == 'SALARY') {
                $totalSalary += $salary->amount;
            }

            if (strtoupper($salary->type) == 'LUNCH') {
                $totalLunch += $salary->amount;
            }
            if (strtoupper($salary->type) == 'CREDITED/ADJUSTED') {
                $totalCredited += $salary->amount;
            }

        }

        return compact('totalSalary', 'totalLunch', 'totalCredited');
    }
}
