<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DuskSeeder extends Seeder
{
    public function run()
    {
        // Create verified user with home location
        DB::table('users')->insert([
            'email' => 'e2e-verified@theflowerpress.net',
            'home_location_id' => 5,
            'created_at' => Carbon::now(),
        ]);
    }
}
