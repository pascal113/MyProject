<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AdminLandingPageMenuItemDynamicRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('menu_items')
            ->where('title', 'Admin Landing Page')
            ->update([
                'url' => '',
                'route' => 'admin.landing',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menu_items')
            ->where('title', 'Admin Landing Page')
            ->update([
                'url' => Config::get('admin.urls.landing'),
                'route' => null,
            ]);
    }
}
