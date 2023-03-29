<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertOrderProductForWashConnectCaching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('modifies_wash_connect_id');
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
            $table->uuid('gateway_id')->nullable()->after('product_variant_id');
            $table->string('modifies_wash_connect_id')->nullable()->after('gateway_id');
        });
    }
}
