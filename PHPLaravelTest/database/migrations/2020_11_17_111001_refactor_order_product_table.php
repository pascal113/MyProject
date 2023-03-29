<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('discount_ea');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('shipping_price_ea');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('tax_ea');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('line_item_total');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->bigInteger('pre_discount_sub_total')->unsigned()->nullable()->after('qty');
            $table->bigInteger('discount')->unsigned()->nullable()->after('pre_discount_sub_total');
            $table->bigInteger('sub_total')->unsigned()->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('pre_discount_sub_total');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('sub_total');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->bigInteger('discount_ea')->unsigned()->default(0)->after('purchase_price_ea');
            $table->bigInteger('shipping_price_ea')->unsigned()->nullable()->after('discount_ea');
            $table->bigInteger('tax_ea')->unsigned()->after('shipping_price_ea');
            $table->bigInteger('line_item_total')->unsigned()->after('qty');
        });
    }
}
