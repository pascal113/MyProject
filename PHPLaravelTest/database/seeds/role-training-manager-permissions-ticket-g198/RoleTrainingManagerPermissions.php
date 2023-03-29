<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleTrainingManagerPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'training-manager')->firstOrFail();

        $permissionsToAdd = [
            Permission::where('key', 'browse_admin')->firstOrFail(),
            Permission::where('key', 'browse_files')->firstOrFail(),
            Permission::where('key', 'edit_files')->firstOrFail(),
            Permission::where('key', 'add_files')->firstOrFail(),
            Permission::where('key', 'delete_files')->firstOrFail(),
            Permission::where('key', 'frontend_company_files')->firstOrFail(),
            Permission::where('key', 'browse_file_categories')->firstOrFail(),
            Permission::where('key', 'read_file_categories')->firstOrFail(),
            Permission::where('key', 'edit_file_categories')->firstOrFail(),
            Permission::where('key', 'add_file_categories')->firstOrFail(),
            Permission::where('key', 'delete_file_categories')->firstOrFail(),
        ];
        foreach ($permissionsToAdd as $permission) {
            $role->permissions()->attach($permission->id);
        }

        User::create([
            'role_id' => $role->id,
            'email' => 'training-manager@theflowerpress.net',
        ]);
    }
}
