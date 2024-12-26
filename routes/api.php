<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettlementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ApiCustomerController;
use App\Http\Controllers\ApiProductController;
use App\Http\Controllers\ApiSupplierController;
use App\Http\Controllers\ApiUserController;

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

// user
 // Get all users
 Route::get('/api-users', [ApiUserController::class, 'index']);

 // Store a new product
 Route::post('/api-users-create', [ApiUserController::class, 'store']);

 // Show a single product
 Route::get('/api-users/{product}', [ApiUserController::class, 'show']);

 // Update a specific product
 Route::post('/api-users-update/{product}', [ApiUserController::class, 'update']);

 // Delete a specific product
 Route::post('/api-users-delete/{product}', [ApiUserController::class, 'destroy']);


// Product CRUD routes with one-time token middleware
Route::middleware(['one-time-token'])->group(function () {
    // Get all products
    Route::get('/api-products', [ApiProductController::class, 'index']);

    // Store a new product
    Route::post('/api-products-create', [ApiProductController::class, 'store']);

    // Show a single product
    Route::get('/api-products/{product}', [ApiProductController::class, 'show']);

    // Update a specific product
    Route::post('/api-products-update/{product}', [ApiProductController::class, 'update']);

    // Delete a specific product
    Route::post('/api-products-delete/{product}', [ApiProductController::class, 'destroy']);


    // Get all Customer
    Route::get('/api-customers', [ApiCustomerController::class, 'index']);

    // Store a new product
    Route::post('/api-customers-create', [ApiCustomerController::class, 'store']);

    // Show a single product
    Route::get('/api-customers/{product}', [ApiCustomerController::class, 'show']);

    // Update a specific product
    Route::post('/api-customers-update/{product}', [ApiCustomerController::class, 'update']);

    // Delete a specific product
    Route::post('/api-customers-delete/{product}', [ApiCustomerController::class, 'destroy']);
    
    // Get all Supplier
Route::get('/api-suppliers', [ApiSupplierController::class, 'index']);

    // Store a new product
    Route::post('/api-suppliers-create', [ApiSupplierController::class, 'store']);

    // Show a single product
    Route::get('/api-suppliers/{product}', [ApiSupplierController::class, 'show']);

    // Update a specific product
    Route::post('/api-suppliers-update/{product}', [ApiSupplierController::class, 'update']);

    // Delete a specific product
    Route::post('/api-suppliers-delete/{product}', [ApiSupplierController::class, 'destroy']);

});

