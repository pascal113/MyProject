<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminOrderStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!$dataType = DB::table('data_types')->where('name', 'orders')->first()) {
            dd('Unable to find orders data type');
        }

        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'status')
            ->update([
                'field' => 'merch_status',
                'display_name' => 'Merch Status',
            ]);
        $merchStatusRow = DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'merch_status')
            ->first();
        DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('order', '>', $merchStatusRow->order)
            ->update([ 'order' => DB::raw('`order` + 1') ]);

        DB::table('data_rows')
            ->insert([
                'data_type_id' => $dataType->id,
                'field' => 'club_status',
                'type' => 'order_status',
                'display_name' => 'Club Status',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => $merchStatusRow->order + 1,
            ]);
    }
}
