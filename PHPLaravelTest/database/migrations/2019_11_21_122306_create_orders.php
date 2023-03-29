<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require_once('ShippingColumns.php');

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->startingAt(1001);

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('user_email')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();

            $table->string('access_token')->nullable();

            $table->bigInteger('discount')->unsigned()->default(0);
            $table->bigInteger('sub_total')->unsigned();
            $table->smallInteger('tax_rate')->unsigned();
            $table->bigInteger('tax')->unsigned();
            $table->bigInteger('total')->unsigned();

            $table->string('status');
            $table->string('transaction_error')->nullable();
            $table->string('transaction_id')->nullable();

            $table->timestamp('shipped_notification_sent_at')->nullable();

            $table->timestamps();
        });

        ShippingColumns::add('orders', 'total');
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
