<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PublishPagesPermissionRoleTableUpdateSeeder extends Seeder
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

        $permission = Permission::where([
            'key' => 'publish_pages_with_category_main',
            'table_name' => 'pages',
        ])->firstOrFail();

        $role->permissions()->attach($permission->id);
    }
}
