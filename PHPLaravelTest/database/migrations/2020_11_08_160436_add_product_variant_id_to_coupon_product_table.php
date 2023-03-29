<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductVariantIdToCouponProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_product', function (Blueprint $table) {
            $table->mediumInteger('product_variant_id')->unsigned()->nullable();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
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
        Schema::table('coupon_product', function (Blueprint $table) {
            $table->dropForeign('coupon_product_product_variant_id_foreign');
            $table->dropColumn('product_variant_id');
        });
    }
}
