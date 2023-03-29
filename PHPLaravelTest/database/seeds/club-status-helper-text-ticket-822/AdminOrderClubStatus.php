<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminOrderClubStatus extends Seeder
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
            ->where('field', 'club_status')
            ->update([
                'details' => '{"helperText": "Ordinarily you should not need to edit this field\'s value - it is updated automatically by internal systems. You do have the ability to edit it if you need to."}',
            ]);
    }
}
