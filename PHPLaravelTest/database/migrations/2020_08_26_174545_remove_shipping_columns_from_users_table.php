<?php

use Illuminate\Database\Migrations\Migration;

require_once('ShippingColumns.php');

class RemoveShippingColumnsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ShippingColumns::drop('users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ShippingColumns::add('users', 'settings');
    }
}
