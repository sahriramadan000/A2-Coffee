<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('cashier_name')->default('No Name')->nullable();
            $table->string('customer_name')->default('No Name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('payment_status', ['Paid', 'Unpaid']);
            $table->string('payment_method');

            $table->integer('total_qty')->min(0);
            $table->integer('subtotal')->min(0)->default(0);
            $table->enum('type_discount', ['price', 'percent'])->nullable();
            $table->integer('price_discount')->min(0)->default(0);
            $table->integer('percent_discount')->min(0)->max(100)->default(0);
            $table->integer('pb01')->default(0);
            $table->integer('service')->default(0);
            $table->integer('total')->default(0);

            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
