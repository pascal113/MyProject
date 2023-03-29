<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicFilesMenuItemsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_items')
            ->where('title', 'COMPANY FILES')
            ->update([
                'title' => 'FILES',
            ]);

        DB::table('menu_items')
            ->where('route', 'voyager.company-files.index')
            ->update([
                'route' => 'voyager.files.index',
            ]);

        DB::table('menu_items')
            ->where('route', 'voyager.company-file-categories.index')
            ->update([
                'route' => 'voyager.file-categories.index',
            ]);
    }
}
