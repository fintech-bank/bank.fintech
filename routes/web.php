<?php

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

Route::get('refund_request', [\App\Http\Controllers\BankController::class, 'refund_request']);
Route::get('status_request', [\App\Http\Controllers\BankController::class, 'status_request']);
Route::get('inter', [\App\Http\Controllers\BankController::class, 'inter']);
