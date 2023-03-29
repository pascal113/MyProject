<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AdminEditSalesTaxPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'key' => 'edit_sales_tax',
            'table_name' => null,
        ]);
    }
}
