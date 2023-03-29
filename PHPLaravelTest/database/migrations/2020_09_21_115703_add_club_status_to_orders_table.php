<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddClubStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('status', 'merch_status');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('merch_status')->nullable()->change();

            $table->string('club_status')->nullable()->after('merch_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('orders')->where('merch_status', null)->update([ 'merch_status' => 'migration-error' ]);

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('club_status');

            $table->string('merch_status')->nullable(false)->change();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('merch_status', 'status');
        });
    }
}
