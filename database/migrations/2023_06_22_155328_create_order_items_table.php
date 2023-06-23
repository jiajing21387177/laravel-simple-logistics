<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('unit_price');
            $table->integer('order_quantity');
            $table->integer('free_quantity');
            $table->integer('total_quantity');
            $table->decimal('total_price');
            $table->integer('customer_product_prices_foc_below_quantity')->default(0);
            $table->integer('customer_product_prices_free_per_quantity_order')->default(0)->comment('How much quantity of product will have free product per order.');
            $table->integer('customer_product_prices_free_quantity')->default(0)->comment('How many quantity will gift every free_per_quantity_order');
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
        Schema::dropIfExists('order_items');
    }
}
