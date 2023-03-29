<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

class DeleteFileDataRowsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $dataType = DB::table('data_types')->where('name', 'files')->first() ?? self::dataTypeNotFound('files');

        DB::table('data_rows')->where([
            'data_type_id' => $dataType->id,
            'field' => 'path',
        ])->update([
            'type' => 'cloud_file',
        ]);
    }
}
