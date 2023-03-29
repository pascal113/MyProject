<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class EssentialPageCategoryPermissionsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $category = 'essential';

        foreach (['browse', 'read', 'edit', 'add', 'delete', 'publish', 'change-url'] as $action) {
            Permission::firstOrCreate([
                'key' => $action.'_pages_with_category_'.$category,
                'table_name' => 'pages',
            ]);
        }
    }
}
