<?php

use Illuminate\Support\Facades\DB;

class RemoveShippingDataRows extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DB::table('data_types')->where('name', 'users')->first() ?? self::dataTypeNotFound('users');

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_first_name')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_last_name')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_address')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_city')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_state')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_zip')
            ->delete();

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'shipping_phone')
            ->delete();
    }
}
