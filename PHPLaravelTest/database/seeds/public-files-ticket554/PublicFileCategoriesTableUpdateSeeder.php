<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicFileCategoriesTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_categories')->insert([
            [
                'slug' => 'public',
                'name' => 'Public',
                'is_public' => true,
                'order' => 10,
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }
}
