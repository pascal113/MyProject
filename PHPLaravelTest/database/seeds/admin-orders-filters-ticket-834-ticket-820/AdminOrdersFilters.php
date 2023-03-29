<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class AdminOrdersFilters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ordersDataType = DataType::where('slug', 'orders')->firstOrFail();

        $ordersDataType->update([ 'server_side' => true ]);

        DataRow::where('data_type_id', $ordersDataType->id)
            ->where('field', 'created_at')
            ->update([ 'browse' => true ]);
    }
}
