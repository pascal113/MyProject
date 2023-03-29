<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

require_once('ShippingColumns.php');

class UsersShippingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ShippingColumns::add('users', 'settings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ShippingColumns::drop('users');
    }
}
