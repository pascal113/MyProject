<?php

use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class ProductVolumePricing extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsDataType = DataType::where('slug', 'products')->firstOrFail();

        DataRow::where('data_type_id', $productsDataType->id)
            ->where('field', 'price')
            ->delete();

        // Don't allow anyone to use the View Product interface - it doesn't really render due to all the dynamic formfields
        $readProductsPermission = Permission::where('key', 'read_products')->firstOrFail();
        DB::table('permission_role')->where('permission_id', $readProductsPermission->id)->delete();

        $products = Product::all();
        foreach ($products as $product) {
            if (!$product->price) {
                continue;
            }

            ProductPrice::create([
                'product_id' => $product->id,
                'qty_from' => 1,
                'qty_to' => null,
                'price_each' => $product->price / 100,
            ]);
        }
    }
}
