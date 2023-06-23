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
            $table->unsignedBigInteger('user_id')->comment('User id (driver).');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->enum('order_status', ['draft', 'confirmed', 'void']);
            $table->unsignedBigInteger('void_by')->nullable();
            $table->enum('delivery_status', ['pending', 'delivered', 'failed'])->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('remark')->nullable();
            $table->text('receipt')->nullable()->comment('File path of the receipt.');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('no action');
            $table->foreign('void_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
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
