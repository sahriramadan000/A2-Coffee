<?php

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OtherSettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
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

// Direct Login
Route::get('/', function () {
    return view('admin.auth.login');
});

// Route::get('/pos', function () {
//     return view('admin.pos.index');
// })->name('pos');

// Detail Transaction
// Route::get('/detail-transaction', [DetailTransactionController::class, 'index'])->name('detail-transaction');

Route::middleware(['auth'])->group(function () {
    // Dahsboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('get-data', [UserController::class, 'getUsers'])->name('get-data');
        Route::get('modal-add', [UserController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('modal-edit/{userId}', [UserController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{userId}', [UserController::class, 'update'])->name('update');
        Route::get('modal-delete/{userId}', [UserController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{userId}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('get-data', [RoleController::class, 'getRoles'])->name('get-data');
        Route::get('modal-add', [RoleController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [RoleController::class, 'store'])->name('store');
        Route::get('modal-edit/{roleId}', [RoleController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{roleId}', [RoleController::class, 'update'])->name('update');
        Route::get('modal-delete/{roleId}', [RoleController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{roleId}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('update-permission', [RoleController::class, 'updatePermissionByID'])->name('update.permission');
        Route::post('update-all-permissions', [RoleController::class, 'updateAllPermissions'])->name('update.permission');
    });

    // Supplier
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('get-data', [SupplierController::class, 'getSuppliers'])->name('get-data');
        Route::get('modal-add', [SupplierController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [SupplierController::class, 'store'])->name('store');
        Route::get('modal-edit/{supplierId}', [SupplierController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{supplierId}', [SupplierController::class, 'update'])->name('update');
        Route::get('modal-delete/{supplierId}', [SupplierController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{supplierId}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    // Material
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('get-data', [MaterialController::class, 'getMaterials'])->name('get-data');
        Route::get('modal-add', [MaterialController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [MaterialController::class, 'store'])->name('store');
        Route::get('modal-edit/{materialId}', [MaterialController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{materialId}', [MaterialController::class, 'update'])->name('update');
        Route::get('modal-delete/{materialId}', [MaterialController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{materialId}', [MaterialController::class, 'destroy'])->name('destroy');
    });

    // Customer
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('get-data', [CustomerController::class, 'getCustomers'])->name('get-data');
        Route::get('modal-add', [CustomerController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [CustomerController::class, 'store'])->name('store');
        Route::get('modal-edit/{customerId}', [CustomerController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{customerId}', [CustomerController::class, 'update'])->name('update');
        Route::get('modal-delete/{customerId}', [CustomerController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{customerId}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    // Attendance
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('get-data', [AttendanceController::class, 'getAttendances'])->name('get-data');
        Route::get('modal-add', [AttendanceController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [AttendanceController::class, 'store'])->name('store');
        Route::get('modal-edit/{attendanceId}', [AttendanceController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{attendanceId}', [AttendanceController::class, 'update'])->name('update');
        Route::get('modal-delete/{attendanceId}', [AttendanceController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{attendanceId}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('check-absensi', [AttendanceController::class, 'checkAbsensi'])->name('check');
    });

    // Product
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('get-data', [ProductController::class, 'getProducts'])->name('get-data');
        Route::get('modal-add', [ProductController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('modal-edit/{productId}', [ProductController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{productId}', [ProductController::class, 'update'])->name('update');
        Route::get('modal-delete/{productId}', [ProductController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{productId}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Report Sales & Absensi
    Route::prefix('report')->name('report.')->group(function () {
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('gross', [ReportController::class, 'reportGross'])->name('report-gross');
            Route::get('gross-data', [ReportController::class, 'getReportGross'])->name('get-report-gross');

            Route::get('payment-method', [ReportController::class, 'paymentMethod'])->name('payment-method');
            Route::get('payment-method-data', [ReportController::class, 'paymentMethod'])->name('get-payment-method');
        });
    });

    // Report Payment Method
    Route::prefix('Payment-method')->name('payment-method.')->group(function () {
        Route::get('/', [ReportController::class, 'paymentMethod'])->name('index');
        Route::get('get-data', [ReportController::class, 'getProducts'])->name('get-data');
    });

    // Tag
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('get-data', [TagController::class, 'getTags'])->name('get-data');
        Route::get('modal-add', [TagController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [TagController::class, 'store'])->name('store');
        Route::get('modal-edit/{tagId}', [TagController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{tagId}', [TagController::class, 'update'])->name('update');
        Route::get('modal-delete/{tagId}', [TagController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{tagId}', [TagController::class, 'destroy'])->name('destroy');
    });

    // Addon
    Route::prefix('addons')->name('addons.')->group(function () {
        Route::get('/', [AddonController::class, 'index'])->name('index');
        Route::get('get-data', [AddonController::class, 'getAddons'])->name('get-data');
        Route::get('modal-add', [AddonController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [AddonController::class, 'store'])->name('store');
        Route::get('modal-edit/{addonId}', [AddonController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{addonId}', [AddonController::class, 'update'])->name('update');
        Route::get('modal-detail/{addonId}', [AddonController::class, 'getModalDetail'])->name('modal-detail');
        Route::get('modal-delete/{addonId}', [AddonController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{addonId}', [AddonController::class, 'destroy'])->name('destroy');
    });

    // Coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('get-data', [CouponController::class, 'getCoupons'])->name('get-data');
        Route::get('modal-add', [CouponController::class, 'getModalAdd'])->name('modal-add');
        Route::post('store', [CouponController::class, 'store'])->name('store');
        Route::get('modal-edit/{couponId}', [CouponController::class, 'getModalEdit'])->name('modal-edit');
        Route::put('update/{couponId}', [CouponController::class, 'update'])->name('update');
        Route::get('modal-delete/{couponId}', [CouponController::class, 'getModalDelete'])->name('modal-delete');
        Route::delete('delete/{couponId}', [CouponController::class, 'destroy'])->name('destroy');
    });

    // Other Setting
    Route::prefix('other-settings')->name('other-settings.')->group(function () {
        Route::get('/', [OtherSettingController::class, 'getModal'])->name('modal');
        Route::put('/{otherSettingId}', [OtherSettingController::class, 'update'])->name('update');
    });

    // Transaction
    Route::get('/transaction', [TransactionController::class, 'index'])->name('pos');
    Route::get('/get-tag', [TransactionController::class, 'getTag'])->name('get-tag');
    Route::get('/get-product/{idTag}', [TransactionController::class, 'getProduct'])->name('get-product');
    Route::get('/modal-add-cart/{productId}', [TransactionController::class, 'modalAddCart'])->name('modal-add-cart');
    Route::get('/modal-add-customer', [TransactionController::class, 'modalAddCustomer'])->name('modal-add-customer');
    Route::post('/get-data-customers', [TransactionController::class, 'getDataCustomers'])->name('get-data-customers');
    Route::get('/modal-search-product', [TransactionController::class, 'modalSearchProduct'])->name('modal-search-product');
    Route::post('/search-product', [TransactionController::class, 'searchProduct'])->name('search-product');
    // Route::get('/modal-add-ongkir', [TransactionController::class, 'modalAddOngkir'])->name('modal-add-ongkir');
    Route::get('/modal-add-discount', [TransactionController::class, 'modalAddDiscount'])->name('modal-add-discount');
    Route::get('/modal-add-coupon', [TransactionController::class, 'modalAddCoupon'])->name('modal-add-coupon');
    Route::get('/modal-my-order', [TransactionController::class, 'modalMyOrder'])->name('modal-my-order');
    Route::post('/add-item',[TransactionController::class, 'addToCart'])->name('add-item');
    Route::post('/add-item-barcode',[TransactionController::class, 'addToCartBarcode'])->name('add-item-barcode');
    Route::post('/update-cart-by-discount',[TransactionController::class, 'updateCartByDiscount'])->name('update-cart-by-discount');
    // Route::post('/update-cart-ongkir',[TransactionController::class, 'updateCartOngkir'])->name('update-cart-ongkir');
    Route::post('/update-cart-by-coupon',[TransactionController::class, 'updateCartByCoupon'])->name('update-cart-by-coupon');
    Route::post('/void-cart',[TransactionController::class, 'voidCart'])->name('void-cart');
    Route::get('/delete-item/{id}',[TransactionController::class, 'deleteItem'])->name('delete-item');
    Route::post('/on-hold-order',[TransactionController::class,'onHoldOrder'])->name('on-hold-order');
    Route::post('/open-on-hold-order',[TransactionController::class,'openOnholdOrder'])->name('open-on-hold-order');
    Route::post('/delete-on-hold-order',[TransactionController::class,'deleteOnholdOrder'])->name('delete-on-hold-order');

    // Checkout
    Route::post('/checkout/{token}',[OrderController::class,'checkout'])->name('checkout-order');
    Route::post('/checkout/checkout-waiters/{token}',[OrderController::class,'checkoutWaiters'])->name('checkout-waiters');

});

