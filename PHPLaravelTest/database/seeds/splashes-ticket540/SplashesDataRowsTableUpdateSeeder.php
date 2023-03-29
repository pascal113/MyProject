<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

class SplashesDataRowsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $dataTypes = collect([
            'splashes',
        ])->reduce(function ($acc, $dataTypeName) {
            $acc[$dataTypeName] = DB::table('data_types')->where('name', $dataTypeName)->first() ?? self::dataTypeNotFound($dataTypeName);

            return $acc;
        }, []);

        DB::table('data_rows')->insert([
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'title',
                'type' => 'text',
                'display_name' => 'Title',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => null,
                'order' => 1,
            ],
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'image',
                'type' => 'image_picker',
                'display_name' => 'Image',
                'required' => 1,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => null,
                'order' => 2,
            ],
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'starts_at',
                'type' => 'timestamp',
                'display_name' => 'Starts at',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => null,
                'order' => 3,
            ],
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'ends_at',
                'type' => 'timestamp',
                'display_name' => 'Ends at',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => null,
                'order' => 4,
            ],
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'link_url',
                'type' => 'text',
                'display_name' => 'Link Url',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => null,
                'order' => 5,
            ],
            [
                'data_type_id' => $dataTypes['splashes']->id,
                'field' => 'is_enabled',
                'type' => 'checkbox',
                'display_name' => 'Enabled?',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"on":"Yes","off":"No"}',
                'order' => 5,
            ],
        ]);
    }
}
