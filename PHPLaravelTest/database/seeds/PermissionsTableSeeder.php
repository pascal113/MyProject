<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key' => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');

        Permission::generateFor('hooks');

        Permission::generateFor('pages');
        foreach (array_keys(config('flexible-page-cms.page_categories')) as $category) {
            foreach (['browse', 'read', 'edit', 'add', 'delete', 'publish', 'change-url'] as $action) {
                Permission::firstOrCreate([
                    'key' => $action.'_pages_with_category_'.$category,
                    'table_name' => 'pages',
                ]);
            }
        }

        Permission::generateFor('products');
        Permission::generateFor('product_categories');
        Permission::generateFor('coupons');
        Permission::generateFor('locations');
        Permission::generateFor('services');
        Permission::generateFor('orders');
        Permission::generateFor('company_file_categories');
        Permission::generateFor('company_files');

        Permission::firstOrCreate([
            'key' => 'frontend_company_files',
            'table_name' => 'company_files',
        ]);
    }
}
