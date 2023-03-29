<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class BipPromoAndLimitedProductVariants extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cleanProduct = Product::findOrFail(14);
        $brightProduct = Product::findOrFail(15);
        $bestProduct = Product::findOrFail(16);
        $cleanForHireProduct = Product::findOrFail(17);
        $brightForHireProduct = Product::findOrFail(18);
        $bestForHireProduct = Product::findOrFail(19);

        // BIP (not For Hire)
        ProductVariant::create([
            'slug' => 'beary-clean-bip-monthly',
            'product_id' => $cleanProduct->id,
            'wash_connect_id' => 'b06722b8-4726-4bce-a8ff-d834c514ed4e',
            'wash_connect_program_id' => '78d93fbd-1200-4a09-b2cc-4153f6bfe17f',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-clean-bip-yearly',
            'product_id' => $cleanProduct->id,
            'wash_connect_id' => '1374b991-4d19-447b-b495-564ef00c89e9',
            'wash_connect_program_id' => '6e863ef7-878a-43db-997c-8101731e6558',
            'name' => 'Yearly BIP',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-bright-bip-monthly',
            'product_id' => $brightProduct->id,
            'wash_connect_id' => '0b4a2483-4e5c-4833-bddd-4f4ab1d36c77',
            'wash_connect_program_id' => '4906ff92-a6f3-4ccc-843e-331dcabd5320',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-best-bip-monthly',
            'product_id' => $bestProduct->id,
            'wash_connect_id' => 'e29f2d85-0b2d-43cb-afd0-4deee4af0c55',
            'wash_connect_program_id' => 'f6ac2273-b06a-4626-a039-eca7511124ce',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);

        // For Hire HIP
        ProductVariant::create([
            'slug' => 'beary-clean-bip-for-hire',
            'product_id' => $cleanForHireProduct->id,
            'wash_connect_id' => 'cc158802-b5fd-4c92-bfac-f4c2870a051a',
            'wash_connect_program_id' => '8819e969-7968-4214-98c4-8e08a370ecb5',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-bright-bip-for-hire',
            'product_id' => $brightForHireProduct->id,
            'wash_connect_id' => '7ce82142-10e7-431a-ab8c-c547839ec263',
            'wash_connect_program_id' => '26ffd1fa-6119-42a5-a3ae-7b4a4d64643c',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-best-bip-for-hire',
            'product_id' => $bestForHireProduct->id,
            'wash_connect_id' => '05227c34-749c-4f40-a646-dba0c2d843b3',
            'wash_connect_program_id' => 'd05aa162-2a1b-4d97-9ea3-65ca339c2777',
            'name' => 'Monthly BIP',
            'is_recurring' => true,
        ]);

        // Promo
        ProductVariant::create([
            'slug' => 'beary-clean-promo',
            'product_id' => $cleanProduct->id,
            'wash_connect_id' => '',
            'wash_connect_program_id' => '1972af30-18b9-4fa4-99c3-826687b6596d',
            'name' => 'Promo',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-clean-limited',
            'product_id' => $cleanProduct->id,
            'wash_connect_id' => '',
            'wash_connect_program_id' => '05535642-b9e8-48d9-a758-0bb37c037b3c',
            'name' => 'Limited',
            'is_recurring' => true,
        ]);
        ProductVariant::create([
            'slug' => 'beary-best-limited',
            'product_id' => $bestProduct->id,
            'wash_connect_id' => '',
            'wash_connect_program_id' => '6e863ef7-878a-43db-997c-8101731e6558',
            'name' => 'Limited',
            'is_recurring' => true,
        ]);
    }
}
