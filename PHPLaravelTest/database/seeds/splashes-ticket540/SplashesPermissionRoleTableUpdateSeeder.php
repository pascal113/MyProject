<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class SplashesPermissionsUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        Permission::generateFor('splashes');

        $permissions = Permission::where(function ($query) {
            $query->where('key', 'LIKE', '%_splashes')
                ->where(function ($query) {
                    $query
                        ->where('key', 'LIKE', 'browse_%')
                        ->orWhere('key', 'LIKE', 'edit_%')
                        ->orWhere('key', 'LIKE', 'add_%')
                        ->orWhere('key', 'LIKE', 'delete_%');
                });
        })->get();

        // Super Admin
        $role = Role::where('name', 'super-admin')->firstOrFail();
        $role->permissions()->attach(
            $permissions->pluck('id')->all()
        );

        // Admin
        $role = Role::where('name', 'admin')->firstOrFail();
        $role->permissions()->attach(
            $permissions->pluck('id')->all()
        );
    }
}
