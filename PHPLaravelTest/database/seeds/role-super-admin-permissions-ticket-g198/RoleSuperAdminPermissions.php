<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSuperAdminPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'super-admin')->firstOrFail();

        $permissionsToAdd = [
            Permission::where('key', 'edit_sales_tax')->firstOrFail(),
        ];
        foreach ($permissionsToAdd as $permission) {
            $role->permissions()->attach($permission->id);
        }

        $permissionsToRemove = [
            Permission::where('key', 'add_orders')->firstOrFail(),
        ];
        foreach ($permissionsToRemove as $permission) {
            $role->permissions()->detach($permission->id);
        }
    }
}
