<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSiteManagerPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'site-manager')->firstOrFail();

        $permissionsToAdd = [
            Permission::where('key', 'frontend_company_files')->firstOrFail(),
        ];
        foreach ($permissionsToAdd as $permission) {
            $role->permissions()->attach($permission->id);
        }

        User::create([
            'role_id' => $role->id,
            'email' => 'site-manager@theflowerpress.net',
        ]);
    }
}
