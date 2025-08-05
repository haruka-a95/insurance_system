<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsurancePolicyController;
use App\Http\Controllers\InsuranceProductController;
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

Route::resource('customers', CustomerController::class);
Route::resource('insurance_policies', InsurancePolicyController::class);
Route::resource('insurance_products', InsuranceProductController::class);