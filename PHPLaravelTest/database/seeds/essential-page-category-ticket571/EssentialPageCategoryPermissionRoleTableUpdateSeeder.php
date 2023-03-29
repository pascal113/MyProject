<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class EssentialPageCategoryPermissionRoleTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::where(function ($query) {
            $query->where('key', 'LIKE', '%_pages_with_category_essential')
                ->where(function ($query) {
                    $query
                        ->where('key', 'LIKE', 'browse_%')
                        ->orWhere('key', 'LIKE', 'edit_%');
                });
        })->get();

        $role->permissions()->attach(
            $permissions->pluck('id')->all()
        );
    }
}
