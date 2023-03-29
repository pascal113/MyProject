<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleStaffPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'staff')->firstOrFail();

        $permissionsToRemove = Permission::whereIn('table_name', [
            'pages',
            'files',
            'file_categories',
        ])->get();
        foreach ($permissionsToRemove as $permission) {
            $role->permissions()->detach($permission->id);
        }
        $morePermissionsToRemove = [
            Permission::where('key', 'edit_products')->firstOrFail(),
            Permission::where('key', 'add_products')->firstOrFail(),
            Permission::where('key', 'delete_products')->firstOrFail(),
            Permission::where('key', 'edit_product_variants')->firstOrFail(),
            Permission::where('key', 'delete_product_variants')->firstOrFail(),
        ];
        foreach ($morePermissionsToRemove as $permission) {
            $role->permissions()->detach($permission->id);
        }

        User::where('email', 'website-editor@theflowerpress.net')->update([
            'role_id' => $role->id,
            'email' => 'staff@theflowerpress.net',
        ]);
    }
}
