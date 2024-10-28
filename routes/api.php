<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettlementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ApiProductController;

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

// Route to generate a token (no middleware needed here)
Route::post('/generate-token', [ApiProductController::class, 'generateToken']);

// Product CRUD routes with one-time token middleware
Route::middleware(['auth:sanctum', 'one-time-token'])->group(function () {
    // Get all products
    Route::get('/api-products', [ApiProductController::class, 'index']);

    // Store a new product
    Route::post('/api-products', [ApiProductController::class, 'store']);

    // Show a single product
    Route::get('/api-products/{product}', [ApiProductController::class, 'show']);

    // Update a specific product
    Route::put('/api-products/{product}', [ApiProductController::class, 'update']);

    // Delete a specific product
    Route::delete('/api-products/{product}', [ApiProductController::class, 'destroy']);
});

