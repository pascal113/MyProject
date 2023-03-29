<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class AllowNonUniqueCouponCodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $couponsDataType = DataType::where('slug', 'coupons')->firstOrFail();

        $codeDataRow = DataRow::where('data_type_id', $couponsDataType->id)
            ->where('field', 'code')
            ->firstOrFail();

        $details = $codeDataRow->details;
        unset($details->validation);
        $codeDataRow->update([ 'details' => $details ]);

        $productsDataRow = DataRow::where('data_type_id', $couponsDataType->id)
            ->where('field', 'coupon_belongstomany_product_relationship')
            ->firstOrFail();
        $productsDataRow->update([ 'browse' => true ]);
    }
}
