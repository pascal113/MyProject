<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class AddLocationMetaSameAsDataRow extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locationsDataType = DataType::where('slug', 'locations')->firstOrFail();

        $metaKeywordsDataRow = DataRow::where('data_type_id', $locationsDataType->id)
            ->where('field', 'meta_keywords')
            ->firstOrFail();

        DataRow::where('data_type_id', $locationsDataType->id)
            ->where('order', '>', $metaKeywordsDataRow->order)
            ->update([ 'order' => DB::raw('`order` + 1') ]);

        DataRow::create([
            'data_type_id' => $locationsDataType->id,
            'field' => 'meta_same_as',
            'type' => 'code_editor',
            'display_name' => 'Meta Same As',
            'required' => false,
            'browse' => false,
            'read' => true,
            'edit' => true,
            'add' => true,
            'delete' => true,
            'details' => (object)[
                'helperText' => 'Enter one URL per line.',
                'validation' => (object)[
                    'rule' => 'nullable|valid_url_on_each_line',
                    'messages' => (object)[
                        'valid_url_on_each_line' => 'Meta Same As must have a valid URL on each line.',
                    ],
                ],
            ],
            'order' => $metaKeywordsDataRow->order + 1,
        ]);
    }
}
