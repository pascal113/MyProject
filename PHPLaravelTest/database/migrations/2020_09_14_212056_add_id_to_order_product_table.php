<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIdToOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::dropIfExists('order_product');

            Schema::create('order_product', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->bigInteger('order_id')->unsigned();
                $table->mediumInteger('product_id')->unsigned();
                $table->mediumInteger('product_variant_id')->unsigned()->nullable();
                $table->uuid('gateway_id')->nullable();

                $table->string('coupon_code')->nullable();

                $table->bigInteger('purchase_price_ea')->unsigned();
                $table->bigInteger('discount_ea')->unsigned()->default(0);
                $table->bigInteger('shipping_price_ea')->unsigned()->nullable();
                $table->bigInteger('tax_ea')->unsigned();
                $table->smallInteger('num_washes_ea')->unsigned()->nullable();
                $table->bigInteger('qty')->unsigned();
                $table->bigInteger('line_item_total')->unsigned();
                $table->text('status_message')->nullable();

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
        } else {
            Schema::table('order_product', function (Blueprint $table) {
                $table->increments('id')->unsigned()->first();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
