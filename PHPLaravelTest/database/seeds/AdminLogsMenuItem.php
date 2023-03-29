<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminLogsMenuItem extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hooksMenuItem = DB::table('menu_items')->where('title', 'Hooks')->first();

        DB::table('menu_items')
            ->where('id', $hooksMenuItem->id)
            ->update([
                'title' => 'Logs',
                'icon_class' => 'voyager-logbook',
                'updated_at' => Carbon::now(),
                'route' => 'voyager.logs',
            ]);
    }
}
