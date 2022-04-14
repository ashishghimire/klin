<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/admin_dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('admin.dashboard');
Route::get('/employee_dashboard', 'App\Http\Controllers\Employee\DashboardController@index')->name('employee.dashboard');

Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
            break;
        case 'employee':
            return redirect()->route('employee.dashboard');
            break;

        default:
            return '/';
            break;
    }
})->middleware(['auth'])->name('dashboard');

Route::resource('customer', \App\Http\Controllers\CustomerController::class)->except(['destroy'])->middleware(['auth']);


Route::delete('/customer/{customer}', 'App\Http\Controllers\CustomerController@destroy')->name('customer.destroy')->middleware(['auth', 'password.confirm']);


Route::resource('customer.bill', \App\Http\Controllers\BillController::class)->only(['create', 'store', 'show'])->middleware(['auth']);

Route::get('bill', 'App\Http\Controllers\BillController@index')->name('bill.index')->middleware(['auth']);

Route::get('bill/{bill}/edit', 'App\Http\Controllers\BillController@edit')->name('bill.edit')->middleware(['auth']);

Route::patch('bill/{bill}/update', 'App\Http\Controllers\BillController@update')->name('bill.update')->middleware(['auth']);

Route::get('invoice/create', 'App\Http\Controllers\BillController@createInvoice')->name('invoice.create')->middleware(['auth']);

//Route::get('invoice-customer/store', 'App\Http\Controllers\CustomerController@createAndRedirectToBilling')->name('invoice.create')->middleware(['auth']);

Route::get('rewards-system', 'App\Http\Controllers\CustomerController@editRewardKey')->name('rewards.edit')->middleware(['auth']);

Route::patch('rewards-system', 'App\Http\Controllers\CustomerController@updateRewardKey')->name('rewards.store')->middleware(['auth']);
require __DIR__ . '/auth.php';

Route::get('income-statement', 'App\Http\Controllers\IncomeController@index')->name('income')->middleware(['auth']);

Route::any('income-statement/search', 'App\Http\Controllers\IncomeController@search')->name('income.search')->middleware(['auth']);


Route::get('import-customer', 'App\Http\Controllers\CustomerController@import')->name('customer.import')->middleware(['auth']);

Route::resource('expense', \App\Http\Controllers\ExpenseController::class)->only(['create', 'store'])->middleware(['auth']);

Route::get('expense-statement', 'App\Http\Controllers\ExpenseController@index')->name('expense.index')->middleware(['auth']);

Route::any('expense-statement/search', 'App\Http\Controllers\ExpenseController@search')->name('expense.search')->middleware(['auth']);


Route::post('change-payment-status/{bill}', 'App\Http\Controllers\BillController@changePaymentStatus')->name('change-payment-status')->middleware(['auth']);

Route::post('change-laundry-status/{bill}', 'App\Http\Controllers\BillController@changeLaundryStatus')->name('change-laundry-status')->middleware(['auth']);

Route::get('customer-export', 'App\Http\Controllers\CustomerController@fileExport')->name('customer-export')->middleware(['auth']);

Route::get('income-export', 'App\Http\Controllers\IncomeController@fileExport')->name('income-export')->middleware(['auth']);

Route::get('expense-export', 'App\Http\Controllers\ExpenseController@fileExport')->name('expense-export')->middleware(['auth']);
