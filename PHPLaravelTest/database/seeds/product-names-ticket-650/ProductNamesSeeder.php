<?php

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::where('name', 'Beary Clean Unlimited Wash Club')
            ->update([ 'name' => 'Clean' ]);

        Product::where('name', 'Beary Bright Unlimited Wash Club')
            ->update([ 'name' => 'Bright' ]);

        Product::where('name', 'Beary Best Unlimited Wash Club')
            ->update([ 'name' => 'Best' ]);

        Product::where('name', 'For Hire Beary Clean Unlimited Wash Club')
            ->update([ 'name' => 'Clean For Hire' ]);

        Product::where('name', 'For Hire Beary Bright Unlimited Wash Club')
            ->update([ 'name' => 'Bright For Hire' ]);

        Product::where('name', 'For Hire Beary Best Unlimited Wash Club')
            ->update([ 'name' => 'Best For Hire' ]);
    }
}
