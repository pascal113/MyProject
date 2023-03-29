<?php

use App\Models\ProductShippingPrice;
use App\Models\ProductVariant;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Permission;

class ProductVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_variants')->delete();
        ProductVariant::insert([
            [
                'slug' => 'beary-clean-monthly',
                'product_id' => 14,
                'wash_connect_id' => '164afb72-315a-4533-9c78-291f0f247f72',
                'wash_connect_program_id' => '7209a74d-eb6b-4267-a62d-41064d53dfd4',
                'name' => 'Monthly',
                'price' => 19.99 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-clean-yearly',
                'product_id' => 14,
                'wash_connect_id' => 'eabb43da-39d6-4d2e-99bb-47e5251cddf0',
                'wash_connect_program_id' => '9987f9a0-f870-4eeb-986c-11be835f023a',
                'name' => 'Yearly',
                'price' => 199.90 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-clean-one-year',
                'product_id' => 14,
                'wash_connect_id' => '006a6892-139a-4776-8a02-4af378297064',
                'wash_connect_program_id' => 'ae35671a-b392-4fc9-bf73-7ff6fbf4f90b',
                'name' => 'One Year',
                'price' => 199.90 * 100,
                'is_recurring' => false,
            ],
            [
                'slug' => 'beary-bright-monthly',
                'product_id' => 15,
                'wash_connect_id' => 'f2ba8a05-74ec-45c1-acfc-3afa69c24018',
                'wash_connect_program_id' => '71ef688c-5917-41aa-b32e-4a25b7c04963',
                'name' => 'Monthly',
                'price' => 29.99 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-bright-yearly',
                'product_id' => 15,
                'wash_connect_id' => '1c46b6d5-4877-424a-bfeb-a611e45ac9e7',
                'wash_connect_program_id' => 'd622a88a-b5cd-4151-b41c-2245fcd5f809',
                'name' => 'Yearly',
                'price' => 290.90 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-bright-one-year',
                'product_id' => 15,
                'wash_connect_id' => 'ef6efe5b-136e-4cb7-b248-f9f853dd36a3',
                'wash_connect_program_id' => 'c01ac228-0fc4-4e72-a28b-2c9fee91d516',
                'name' => 'One Year',
                'price' => 290.90 * 100,
                'is_recurring' => false,
            ],
            [
                'slug' => 'beary-best-monthly',
                'product_id' => 16,
                'wash_connect_id' => 'c7246447-d88e-457d-af8f-5e29111f21a0',
                'wash_connect_program_id' => '95c42308-ffa4-4352-8cb3-c275745bac16',
                'name' => 'Monthly',
                'price' => 39.99 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-best-yearly',
                'product_id' => 16,
                'wash_connect_id' => 'a6d77a2c-64b0-4c3e-a1bc-11995c2fff25',
                'wash_connect_program_id' => '8ba58f62-78bf-40b1-8757-9615797b92c7',
                'name' => 'Yearly',
                'price' => 390.90 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-best-one-year',
                'product_id' => 16,
                'wash_connect_id' => '972bdf1d-4d8f-469c-90e4-89e8d99c3051',
                'wash_connect_program_id' => '5e7536b4-bf0b-48eb-b85c-4f9fc62b273a',
                'name' => 'One Year',
                'price' => 390.90 * 100,
                'is_recurring' => false,
            ],
            [
                'slug' => 'beary-clean-for-hire',
                'product_id' => 17,
                'wash_connect_id' => '674ed3c4-49ec-46f8-89a6-522a16fb6f7d',
                'wash_connect_program_id' => 'f5e214ba-e32f-468a-8fd2-4b939bf8a7ed',
                'name' => 'Monthly',
                'price' => 36.99 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-bright-for-hire',
                'product_id' => 18,
                'wash_connect_id' => 'b2c3f885-4f76-4025-8150-c2efe2af6238',
                'wash_connect_program_id' => '10e9efc8-dd90-4cd4-8c87-fb59959a543c',
                'name' => 'Monthly',
                'price' => 47.99 * 100,
                'is_recurring' => true,
            ],
            [
                'slug' => 'beary-best-for-hire',
                'product_id' => 19,
                'wash_connect_id' => '602e2d6d-fdef-4993-88f3-43d78bf67a34',
                'wash_connect_program_id' => '43394ede-8945-4f00-af88-bdafa4657a9a',
                'name' => 'Monthly',
                'price' => 59.99 * 100,
                'is_recurring' => true,
            ],
        ]);

        ProductShippingPrice::where('product_id', '>=', 14)
            ->where('product_id', '<=', 19)
            ->update([ 'price_each' => 0 ]);

        DB::table('data_types')->where('slug', 'products')->update([ 'icon' => 'voyager-tree' ]);
        if ($variantsDataType = DataType::create([
            'name' => 'product_variants',
            'slug' => 'product-variants',
            'display_name_singular' => 'Product Variant',
            'display_name_plural' => 'Product Variants',
            'icon' => 'voyager-trees',
            'model_name' => \App\Models\ProductVariant::class,
            'policy_name' => null,
            'controller' => \App\Http\Controllers\Admin\ProductVariantController::class,
            'description' => null,
            'generate_permissions' => 1,
            'server_side' => 0,
            'details' => '{"order_column":"name","order_display_column":null,"order_direction":"asc","default_search_key":null}',
            'created_at' => '2020-08-03 16:20:07',
            'updated_at' => '2020-08-03 16:20:07',
        ])) {
            if ($productsMenuItem = DB::table('menu_items')->where('route', 'voyager.products.index')->first()) {
                DB::table('menu_items')
                    ->where('menu_id', 1)
                    ->where('parent_id', null)
                    ->where('order', '>', $productsMenuItem->order)
                    ->update([ 'order' => DB::raw('`order` + 1') ]);
                DB::table('menu_items')->insert([
                    'menu_id' => 1,
                    'title' => 'Product Variants',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-trees',
                    'color' => null,
                    'parent_id' => null,
                    'order' => ($productsMenuItem->order ?? 0) + 1,
                    'created_at' => '2020-08-03 16:24:25',
                    'updated_at' => '2020-08-03 16:24:25',
                    'route' => 'voyager.product-variants.index',
                    'parameters' => null,
                ]);
            }

            if ($productsDataType = DB::table('data_types')->where('slug', 'products')->first()) {
                DB::table('data_rows')
                    ->where('data_type_id', $productsDataType->id)
                    ->where('field', 'price_monthly')
                    ->delete();
                DB::table('data_rows')
                    ->where('data_type_id', $productsDataType->id)
                    ->where('field', 'price_annual')
                    ->delete();
            }

            DB::table('data_rows')->insert([
                [
                    'data_type_id' => $variantsDataType->id,
                    'field' => 'name',
                    'type' => 'text',
                    'display_name' => 'Name',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => '{"link": {"edit": "edit", "read": "show"}}',
                    'order' => 2,
                ],
                [
                    'data_type_id' => $variantsDataType->id,
                    'field' => 'price',
                    'type' => 'number',
                    'display_name' => 'Price',
                    'required' => 1,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'details' => null,
                    'order' => 3,
                ],
                [
                    'data_type_id' => $variantsDataType->id,
                    'field' => 'created_at',
                    'type' => 'timestamp',
                    'display_name' => 'Created At',
                    'required' => 0,
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 0,
                    'add' => 0,
                    'delete' => 0,
                    'details' => null,
                    'order' => 4,
                ],
                [
                    'data_type_id' => $variantsDataType->id,
                    'field' => 'updated_at',
                    'type' => 'timestamp',
                    'display_name' => 'Updated At',
                    'required' => 0,
                    'browse' => 0,
                    'read' => 0,
                    'edit' => 0,
                    'add' => 0,
                    'delete' => 0,
                    'details' => null,
                    'order' => 5,
                ],
            ]);

            if ($superAdmin = Role::where('name', 'super-admin')->first()) {
                Permission::generateFor('product_variants');

                $permissions = Permission::where(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_product_variants')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'read_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })->get();

                $superAdmin->permissions()->attach(
                    $permissions->pluck('id')->all()
                );
            }
        }
    }
}
