<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned();
            $table->mediumInteger('product_id')->unsigned();
            $table->string('size')->nullable();

            $table->string('coupon_code')->nullable();

            $table->bigInteger('purchase_price_ea')->unsigned();
            $table->bigInteger('discount_ea')->unsigned()->default(0);
            $table->bigInteger('tax_ea')->unsigned();
            $table->smallInteger('num_washes_ea')->unsigned()->nullable();
            $table->bigInteger('qty')->unsigned();
            $table->bigInteger('line_item_total')->unsigned();

            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
