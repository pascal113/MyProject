<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddShippedAtColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('shipped_notification_sent_at', 'shipping_notification_sent_at');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('orders', function () {
                DB::statement('ALTER TABLE orders MODIFY COLUMN shipping_notification_sent_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at');
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('shipped_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipped_at');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('orders', function () {
                DB::statement('ALTER TABLE orders MODIFY COLUMN shipping_notification_sent_at TIMESTAMP AFTER transaction_id');
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('shipping_notification_sent_at', 'shipped_notification_sent_at');
        });
    }
}
