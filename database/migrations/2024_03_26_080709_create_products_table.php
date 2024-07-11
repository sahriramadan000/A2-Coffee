<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('slug');
            $table->string('category');
            $table->text('description')->nullable();
            $table->float('cost_price')->default(0);
            $table->float('selling_price')->default(0);
            $table->text('picture')->nullable();
            $table->boolean('is_discount')->default(false);
            $table->integer('percent_discount')->default(0);
            $table->float('price_discount')->default(0);
            $table->bigInteger('stock_per_day')->default(0);
            $table->bigInteger('current_stock')->default(0);
            $table->bigInteger('minimum_stock')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('products');
    }
}
