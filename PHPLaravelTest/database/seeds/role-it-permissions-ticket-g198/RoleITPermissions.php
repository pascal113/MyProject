<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleITPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'it')->firstOrFail();

        $permissionsToAdd = [
            Permission::where('key', 'browse_admin')->firstOrFail(),
            Permission::where('key', 'browse_products')->firstOrFail(),
            Permission::where('key', 'edit_products')->firstOrFail(),
            Permission::where('key', 'browse_product_variants')->firstOrFail(),
            Permission::where('key', 'read_product_variants')->firstOrFail(),
            Permission::where('key', 'browse_product_categories')->firstOrFail(),
            Permission::where('key', 'browse_coupons')->firstOrFail(),
            Permission::where('key', 'browse_orders')->firstOrFail(),
            Permission::where('key', 'read_orders')->firstOrFail(),
            Permission::where('key', 'browse_files')->firstOrFail(),
            Permission::where('key', 'edit_files')->firstOrFail(),
            Permission::where('key', 'add_files')->firstOrFail(),
            Permission::where('key', 'delete_files')->firstOrFail(),
            Permission::where('key', 'frontend_company_files')->firstOrFail(),
            Permission::where('key', 'browse_file_categories')->firstOrFail(),
            Permission::where('key', 'edit_file_categories')->firstOrFail(),
            Permission::where('key', 'add_file_categories')->firstOrFail(),
            Permission::where('key', 'delete_file_categories')->firstOrFail(),
            Permission::where('key', 'browse_splashes')->firstOrFail(),
            Permission::where('key', 'edit_sales_tax')->firstOrFail(),
        ];
        foreach ($permissionsToAdd as $permission) {
            $role->permissions()->attach($permission->id);
        }

        User::create([
            'role_id' => $role->id,
            'email' => 'it@theflowerpress.net',
        ]);
    }
}
