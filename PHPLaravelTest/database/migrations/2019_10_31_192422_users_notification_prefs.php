<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersNotificationPrefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('notification_pref_orders')->unsigned()->default(1)->after('shipping_phone');
            $table->tinyInteger('notification_pref_promotions')->unsigned()->default(1)->after('notification_pref_orders');
            $table->tinyInteger('notification_pref_marketing')->unsigned()->default(1)->after('notification_pref_promotions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_pref_orders');
            $table->dropColumn('notification_pref_promotions');
            $table->dropColumn('notification_pref_marketing');
        });
    }
}
