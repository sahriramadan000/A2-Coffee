<?php

use App\Http\Controllers\Admin\SettlementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/print-customer/{id}', [TransactionController::class, 'printCustomer'])->name('print-customer'); 
Route::post('/print-settlement', [SettlementController::class, 'printSettlement'])->name('api-print-settlement'); 

