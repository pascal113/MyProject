<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameCompanyFilesTableToFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('company_files', 'files');

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('files', function (Blueprint $table) {
                $table->dropForeign('company_files_company_file_category_id_foreign');
            });
        }

        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('company_file_category_id', 'file_category_id');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->foreign('file_category_id')
                ->references('id')
                ->on('file_categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::rename('files', 'company_files');

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('company_files', function (Blueprint $table) {
                $table->dropForeign('files_file_category_id_foreign');
            });
        }

        Schema::table('company_files', function (Blueprint $table) {
            $table->renameColumn('file_category_id', 'company_file_category_id');
        });

        Schema::table('company_files', function (Blueprint $table) {
            $table->foreign('company_file_category_id')
                ->references('id')
                ->on('file_categories')
                ->onDelete('cascade');
        });
    }
}
