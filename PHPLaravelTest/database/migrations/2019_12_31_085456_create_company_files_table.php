<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('company_file_category_id')->unsigned();
            $table->string('name');
            $table->string('path');
            $table->string('thumbnail')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('order')->unsigned();
            $table->timestamps();

            $table->foreign('company_file_category_id')
                ->references('id')
                ->on('company_file_categories')
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
        Schema::dropIfExists('company_files');
    }
}
