<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShippingColumns
{
    public static function add(string $tableName, ?string $after = null): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($after) {
            $table->string('shipping_first_name', 255)->nullable()->after($after);
            $table->string('shipping_last_name', 255)->nullable()->after($after ? 'shipping_first_name' : null);
            $table->string('shipping_address')->nullable()->after($after ? 'shipping_last_name' : null);
            $table->string('shipping_city', 255)->nullable()->after($after ? 'shipping_address' : null);
            $table->string('shipping_state', 2)->nullable()->after($after ? 'shipping_city' : null);
            $table->string('shipping_zip', 10)->nullable()->after($after ? 'shipping_state' : null);
            $table->string('shipping_phone', 20)->nullable()->after($after ? 'shipping_zip' : null);
        });
    }

    public static function drop(string $tableName): void
    {
        if (Schema::hasColumn($tableName, 'shipping_name')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('shipping_name');
            });
        }

        if (Schema::hasColumn($tableName, 'shipping_first_name')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('shipping_first_name');
            });
        }

        if (Schema::hasColumn($tableName, 'shipping_last_name')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('shipping_last_name');
            });
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('shipping_address');
        });

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('shipping_city');
        });

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('shipping_state');
        });

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('shipping_zip');
        });

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('shipping_phone');
        });
    }
}
