<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    /**
     * @var ExpenseCategory
     */
    protected $category;

    /**
     * ExpenseCategoryController constructor.
     * @param ExpenseCategory $category
     */


    public function index()
    {
        $categories = ExpenseCategory::all();

        return view('expense-category.index', compact('categories'));
    }

    public function create()
    {
        return view('expense-category.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            ExpenseCategory::create($data);

            DB::commit();
            return redirect()->route('expense-category.index');

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return redirect()->back()->withErrors('There was a problem in creating expense category');
        }
    }

    public function edit($category)
    {
        $category = ExpenseCategory::find($category);

        return view('expense-category.edit', compact('category'));
    }

    public function update($category)
    {
        $data = request()->all();

        $category = ExpenseCategory::find($category);

        $category->update($data);

        return redirect()->route('expense-category.index');

    }

    public function destroy($category)
    {
        $category = ExpenseCategory::find($category);

        $category->delete();

        return redirect()->route('expense-category.index');

    }
}

