<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('cost_price')->default(0);
            $table->float('selling_price')->default(0);
            $table->boolean('is_discount')->default(false);
            $table->integer('percent_discount')->default(0);
            $table->float('price_discount')->default(0);
            $table->integer('qty')->min(0);

            $table->bigInteger("order_id")->unsigned()->nullable();
            $table->foreign("order_id")->references("id")->on("orders")->onDelete('cascade');
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
        Schema::dropIfExists('order_products');
    }
}
