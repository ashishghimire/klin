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
    return view('welcome');
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

require __DIR__ . '/auth.php';
