<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocationsPriceRange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function ($table) {
            $table->string('price_range')->nullable()->after('manager_title');
        });
        Schema::table('location_service', function ($table) {
            $table->string('price_range')->nullable()->after('day_7_closes_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('price_range');
        });
        Schema::table('location_service', function ($table) {
            $table->dropColumn('price_range');
        });
    }
}
