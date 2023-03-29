<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductShippingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shipping_prices', function (Blueprint $table) {
            $table->mediumInteger('product_id')->unsigned();
            $table->smallInteger('qty_from')->nullable();
            $table->smallInteger('qty_to')->nullable();
            $table->bigInteger('price_each')->nullable();
            $table->timestamps();

            $table->foreign('product_id', 'product_id_foreign')
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
        Schema::dropIfExists('product_shipping_prices');
    }
}
