<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'dashboard',

            // User Permission
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role Permission
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Supplier Permission
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',

            // Attendance Permission
            'attendance-list',
            'attendance-create',
            'attendance-edit',
            'attendance-delete',

            // Order Pesanan Permission
            'order-pesanan',

            // Settlement Permission
            'settlement',

            // Material Permission
            'material-list',
            'material-create',
            'material-edit',
            'material-delete',

            // Product Permission
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            // Tag Permission
            'tag-list',
            'tag-create',
            'tag-edit',
            'tag-delete',

            // Addon Permission
            'addon-list',
            'addon-create',
            'addon-edit',
            'addon-delete',

            // Coupon Permission
            'coupon-list',
            'coupon-create',
            'coupon-edit',
            'coupon-delete',

            // Other Setting Permission
            'other-setting',

            // Customer Permission
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            // Table Permission
            'table-list',
            'table-create',
            'table-edit',
            'table-delete',

            // POS
            'discount',
            'void',
            'coupon',
            'delete-product-in-cart',
            'simpan-order',
            'detail-penjualan',

            // Report Permission
            'report-gross-profit',
            'report-payment-method',
            'report-refund',
            'report-absensi',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
    }
}
