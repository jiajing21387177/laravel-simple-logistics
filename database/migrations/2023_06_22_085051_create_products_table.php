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
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('foc_below_quantity')->default(0);
            $table->integer('free_per_quantity_order')->default(0)->comment('How much quantity of product will have free product per order.');
            $table->integer('free_quantity')->default(0)->comment('How many quantity will gift every free_per_quantity_order');
            $table->integer('stock');
            $table->string('image')->nullable();
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
        Schema::table('customer_product', function (Blueprint $table) {
            $table->dropForeign('product_id');
        });
        Schema::dropIfExists('products');
    }
}
