<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveLocation1014 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->where('site_number', '1014')->delete();
    }
}
