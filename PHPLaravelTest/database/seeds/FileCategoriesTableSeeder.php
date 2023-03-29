<?php

use Illuminate\Database\Seeder;

class FileCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('file_categories')->delete();

        \DB::table('file_categories')->insert([
            [
                'slug' => 'safety-documents',
                'name' => 'Safety Documents',
                'order' => 1,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'training-documents',
                'name' => 'Training Documents',
                'order' => 2,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'training-videos',
                'name' => 'Training Videos',
                'order' => 3,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'c-store-tasks-procedures',
                'name' => 'C-Store Tasks & Procedures',
                'order' => 4,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'miscellaneous-forms',
                'name' => 'Miscellaneous Forms',
                'order' => 5,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => '401k-forms',
                'name' => '401(k) Forms',
                'order' => 6,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'new-hire-forms',
                'name' => 'New Hire Forms',
                'order' => 7,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'temporary-new-hire-summary',
                'name' => 'Temporary New Hire Summary',
                'order' => 8,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'policy-documents',
                'name' => 'Policy Documents',
                'order' => 9,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'slug' => 'benefits',
                'name' => 'Benefits',
                'order' => 10,
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }
}
