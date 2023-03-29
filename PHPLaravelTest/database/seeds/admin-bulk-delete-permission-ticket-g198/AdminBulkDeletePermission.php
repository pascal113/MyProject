<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminBulkDeletePermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create([
            'key' => 'bulk_delete',
            'table_name' => null,
        ]);

        $rolesToGivePermissionTo = Role::whereIn('name', [
            'super-admin',
            'admin',
        ])->get();

        foreach ($rolesToGivePermissionTo as $role) {
            $role->permissions()->attach($permission->id);
        }
    }
}
