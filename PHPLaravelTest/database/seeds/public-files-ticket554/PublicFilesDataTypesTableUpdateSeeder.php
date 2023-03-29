<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicFilesDataTypesTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_types')
            ->where('slug', 'company-files')
            ->update([
                'name' => 'files',
                'slug' => 'files',
                'display_name_singular' => 'File',
                'display_name_plural' => 'Files',
                'model_name' => \App\Models\File::class,
                'controller' => \App\Http\Controllers\Admin\FileController::class,
            ]);
        DB::table('data_types')
            ->where('slug', 'company-file-categories')
            ->update([
                'name' => 'file_categories',
                'slug' => 'file-categories',
                'display_name_singular' => 'File Category',
                'display_name_plural' => 'File Categories',
                'model_name' => \App\Models\FileCategory::class,
                'controller' => \App\Http\Controllers\Admin\FileCategoryController::class,
            ]);
    }
}
