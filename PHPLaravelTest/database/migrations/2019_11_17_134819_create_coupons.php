<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('code')->unique();
            $table->tinyInteger('percent_discount')->unsigned();
            $table->smallInteger('minimum_cart_total')->unsigned();
            $table->smallInteger('num_uses')->unsigned()->default(0);
            $table->smallInteger('expires_after_num_uses')->unsigned();
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
