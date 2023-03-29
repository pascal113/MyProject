<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SSOUpdatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DB::table('data_types')->where('name', 'users')->first() ?? self::dataTypeNotFound('users');

        /**
         * Remove `avatar`, `password`, and `remember_token` Voyager data rows for Users
         */
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'avatar')
            ->delete();
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'password')
            ->delete();
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'remember_token')
            ->delete();

        /**
         * Make `first_name`, `last_name`, `password`, and all `shipping_*` Voyager data rows for Users read-only
         */
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'first_name')
            ->update([
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
            ]);
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'last_name')
            ->update([
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
            ]);
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'password')
            ->update([
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
            ]);
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'LIKE', 'shipping_%')
            ->update([
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
            ]);

        /**
         * Add Admin Landing Page menu item
         */
        DB::table('menu_items')->insert([
            'menu_id' => 1,
            'title' => 'Admin Landing Page',
            'url' => Config::get('admin.urls.landing'),
            'target' => '_self',
            'icon_class' => 'voyager-activity',
            'color' => null,
            'parent_id' => null,
            'order' => 0,
            'created_at' => '2020-07-20 11:21:38',
            'updated_at' => '2020-07-20 11:21:38',
            'route' => null,
            'parameters' => null,
        ]);
    }
}
