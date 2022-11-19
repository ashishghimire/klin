<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin')->except('show');
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
        if (auth()->user()->role == 'admin' || auth()->user()->id == $employee->id)
            return view('employee.show', compact('employee'));

        return redirect()->route('employee.index')->with('message', "You are not allowed to view this page");
    }
}
