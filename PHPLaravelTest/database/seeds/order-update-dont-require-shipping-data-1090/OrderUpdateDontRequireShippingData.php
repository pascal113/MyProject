<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class OrderUpdateDontRequireShippingData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ordersDataType = DataType::where('slug', 'orders')->firstOrFail();

        DataRow::where('data_type_id', $ordersDataType->id)
            ->whereIn('field', [
                'shipping_first_name',
                'shipping_last_name',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_zip',
                'shipping_phone',
            ])
            ->update([ 'required' => 0 ]);
    }
}
