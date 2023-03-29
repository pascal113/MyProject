<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class AddShippedAtColumnToOrdersAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DataType::where('slug', 'orders')->firstOrFail();

        $updatedAtDataRow = DataRow::where('data_type_id', $dataType->id)
            ->where('field', 'updated_at')
            ->firstOrFail();
        $updatedAtDataRow->update([ 'read' => true ]);

        $shippingNotificationSentAtDataRow = DataRow::where('data_type_id', $dataType->id)
            ->where('field', 'shipped_notification_sent_at')
            ->firstOrFail();
        $shippingNotificationSentAtDataRow->update([
            'field' => 'shipping_notification_sent_at',
            'edit' => true,
            'order' => $updatedAtDataRow->order + 2,
        ]);

        DataRow::create([
            'data_type_id' => $dataType->id,
            'field' => 'shipped_at',
            'type' => 'timestamp',
            'display_name' => 'Shipped At',
            'required' => false,
            'browse' => true,
            'read' => true,
            'edit' => true,
            'add' => true,
            'delete' => true,
            'order' => $updatedAtDataRow->order + 1,
        ]);

        DataRow::where('data_type_id', $dataType->id)
            ->where('order', '>=', $updatedAtDataRow->order)
            ->update([ 'order' => DB::raw('`order` - 1') ]);
    }
}
