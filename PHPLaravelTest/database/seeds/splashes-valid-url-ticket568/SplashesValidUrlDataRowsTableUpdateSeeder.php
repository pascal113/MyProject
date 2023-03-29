<?php

use Illuminate\Support\Facades\DB;

class SplashesValidUrlDataRowsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $dataType = DB::table('data_types')->where('name', 'splashes')->first() ?? self::dataTypeNotFound('splashes');

        DB::table('data_rows')->where([
            'data_type_id' => $dataType->id,
            'field' => 'link_url',
        ])->update(['type' => 'url']);
    }
}
