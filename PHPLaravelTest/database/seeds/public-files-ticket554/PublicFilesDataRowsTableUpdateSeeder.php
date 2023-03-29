<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

class PublicFilesDataRowsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $dataTypes = collect([
            'file_categories',
            'files',
        ])->reduce(function ($acc, $dataTypeName) {
            $acc[$dataTypeName] = DB::table('data_types')->where('name', $dataTypeName)->first() ?? self::dataTypeNotFound($dataTypeName);

            return $acc;
        }, []);

        DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['file_categories']->id,
            'field' => 'slug',
        ])->update([
            'details' => '{"validation":{"rule":"unique:file_categories,slug","messages":{"unique": "Please use a unique slug."}},"slugify": {"origin": "name","forceUpdate": false}}',
        ]);

        $slugOrder = DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['file_categories']->id,
            'field' => 'slug',
        ])->first()->order;

        DB::table('data_rows')
            ->where('data_type_id', $dataTypes['file_categories']->id)
            ->where('order', '>', $slugOrder)
            ->update([
                'order' => DB::raw('`order` + 1'),
            ]);

        DB::table('data_rows')->insert([
            'data_type_id' => $dataTypes['file_categories']->id,
            'field' => 'is_public',
            'type' => 'checkbox',
            'display_name' => 'Public?',
            'required' => false,
            'browse' => true,
            'read' => true,
            'edit' => true,
            'add' => true,
            'delete' => true,
            'details' => '{"on":"Yes","off":"No"}',
            'order' => $slugOrder + 1,
        ]);

        DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['file_categories']->id,
            'field' => 'company_file_category_hasmany_company_file_relationship',
        ])->update([
            'field' => 'file_category_hasmany_file_relationship',
            'details' => '{"model":"App\\\\Models\\\\File","table":"files","type":"hasMany","column":"file_category_id","key":"id","label":"name","pivot_table":"data_rows","pivot":"0","taggable":null}',
        ]);

        DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['files']->id,
            'field' => 'company_file_category_id',
        ])->update([
            'field' => 'file_category_id',
        ]);

        DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['files']->id,
            'field' => 'company_file_belongsto_company_file_category_relationship',
        ])->update([
            'field' => 'file_belongsto_company_file_category_relationship',
            'details' => '{"model":"App\\\\Models\\\\FileCategory","table":"file_categories","type":"belongsTo","column":"file_category_id","key":"id","label":"name","pivot_table":"file_categories","pivot":"0","taggable":null}',
        ]);

        DB::table('data_rows')->where([
            'data_type_id' => $dataTypes['files']->id,
            'field' => 'path',
        ])->update([
            'type' => 'file',
        ]);
    }
}
