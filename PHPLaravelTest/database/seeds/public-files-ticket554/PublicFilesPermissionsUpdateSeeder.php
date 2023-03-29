<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicFilesPermissionsUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')
            ->where('key', 'not like', 'frontend_%')
            ->where(function ($query) {
                return $query->where('key', 'like', '%_company_files')
                    ->orWhere('key', 'like', '%_company_file_categories');
            })
            ->update([
                'key' => DB::raw('REPLACE(`key`, "company_", "")'),
                'table_name' => DB::raw('REPLACE(`table_name`, "company_", "")'),
            ]);
    }
}
