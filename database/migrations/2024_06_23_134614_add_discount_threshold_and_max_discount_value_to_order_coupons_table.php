<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountThresholdAndMaxDiscountValueToOrderCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_coupons', function (Blueprint $table) {
            $table->float('discount_threshold')->nullable()->after('discount_value');
            $table->float('max_discount_value')->nullable()->after('discount_threshold');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_coupons', function (Blueprint $table) {
            $table->dropColumn('discount_threshold');
            $table->dropColumn('max_discount_value');
        });
    }
}
