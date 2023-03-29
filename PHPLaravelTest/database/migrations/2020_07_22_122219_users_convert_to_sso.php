<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersConvertToSso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('remember_token');
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
            $table->string('first_name')->after('role_id');
            $table->string('last_name')->after('first_name');
            $table->string('avatar')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('avatar');
            $table->string('password')->nullable()->after('email_verified_at');
            $table->string('remember_token', 100)->nullable()->after('password');
        });
    }
}
