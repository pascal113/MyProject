<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocationServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_service', function (Blueprint $table) {
            $table->bigInteger('location_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
            for ($i = 1; $i <= 7; $i++) {
                $table->mediumInteger('day_'.$i.'_opens_at')->unsigned()->nullable();
                $table->mediumInteger('day_'.$i.'_closes_at')->unsigned()->nullable();
            }
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
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
        Schema::dropIfExists('location_service');
    }
}
