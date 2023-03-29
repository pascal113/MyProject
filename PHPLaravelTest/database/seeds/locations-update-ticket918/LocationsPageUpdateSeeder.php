<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class LocationsAdminUpdateSeeder extends Seeder
{
    public function run()
    {
        $dataType = DataType::where('name', 'locations')->firstOrFail();

        $siteNumberRow = DataRow::where('data_type_id', $dataType->id)
            ->where('field', 'site_number')
            ->firstOrFail();
        DataRow::where('data_type_id', $dataType->id)
            ->where('order', '>', $siteNumberRow->order)
            ->update([ 'order' => DB::raw('`order` + 1') ]);

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'wash_connect_id',
            'type' => 'text',
            'display_name' => 'WashConnect Id',
            'required' => 0,
            'browse' => 0,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'details' => (object)[ 'helperText' => 'Edits to this field will not update WashConnect. This is used for reference and website functionality only.' ],
            'order' => $siteNumberRow->order + 1,
        ]);
    }
}
