<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('site_number', 10);
            $table->point('lng_lat');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('phone');
            $table->string('manager_name')->nullable();
            $table->string('manager_title')->nullable();
            $table->string('meta_title', 128)->nullable();
            $table->string('meta_description', 256)->nullable();
            $table->string('meta_keywords', 256)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
