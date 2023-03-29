<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class AddLocationsTemporarilyClosedDataRow extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locationsDataType = DataType::where('slug', 'locations')->firstOrFail();

        $priceRangeDataRow = DataRow::where('data_type_id', $locationsDataType->id)
            ->where('field', 'price_range')
            ->firstOrFail();

        DataRow::where('data_type_id', $locationsDataType->id)
            ->where('order', '>', $priceRangeDataRow->order)
            ->update([ 'order' => DB::raw('`order` + 1') ]);

        DataRow::create([
            'data_type_id' => $locationsDataType->id,
            'field' => 'temporarily_closed',
            'type' => 'checkbox',
            'display_name' => 'Temporarily Closed?',
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'details' => (object)[
                'on' => 'Yes',
                'off' => 'No',
            ],
            'order' => $priceRangeDataRow->order + 1,
        ]);
    }
}
