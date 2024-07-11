<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price')->nullable();

            $table->bigInteger("order_product_id")->unsigned()->nullable();
            $table->foreign("order_product_id")->references("id")->on("order_products")->onDelete('cascade');
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
        Schema::dropIfExists('order_product_addons');
    }
}
