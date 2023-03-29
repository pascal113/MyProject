<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAdminPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissionsToAdd = [
            Permission::where('key', 'edit_sales_tax')->firstOrFail(),
        ];
        foreach ($permissionsToAdd as $permission) {
            $role->permissions()->attach($permission->id);
        }
    }
}
