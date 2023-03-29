<?php

use Illuminate\Database\Seeder;

class ProductCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_categories')->delete();

        \DB::table('product_categories')->insert([
            [
                'id' => 1,
                'slug' => 'memberships',
                'name' => 'Memberships',
            ],
            [
                'id' => 2,
                'slug' => 'paw-packs-ticket-books',
                'name' => 'Paw Packs & Ticket Books',
            ],
            [
                'id' => 3,
                'slug' => 'branded-merchandise',
                'name' => 'Branded Merchandise',
            ],
        ]);
    }
}
