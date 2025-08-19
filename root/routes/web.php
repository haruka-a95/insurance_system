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

// 保険製品登録用CSVアップロード
Route::post('insurance_product', [InsuranceProductController::class, 'import'])->name('insurance_product.import');

//ポリシーCSV 全件出力
Route::get('insurance-policy/export-csv', [InsurancePolicyController::class, 'exportCsv'])->name('insurance_policies.export_csv');
//ポリシー検索条件から抜粋して出力
Route::get('insurance_policy/filtered-csv', [InsurancePolicyController::class, 'exportFiltered'])->name('insurance_policies.export_filtered');