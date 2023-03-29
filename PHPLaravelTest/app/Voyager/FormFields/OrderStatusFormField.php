<?php

namespace App\Voyager\FormFields;

use App\Models\Order;
use TCG\Voyager\FormFields\AbstractHandler;

class OrderStatusFormField extends AbstractHandler
{
    /**
     * Name of the form field type as visible to admin creating when a BREAD
     *
     * @var string
     */
    protected $codename = 'order_status';

    /**
     * Specify the view
     *
     * @param TCG\Voyager\Models\DataRow $row
     * @param TCG\Voyager\Models\DataType $dataType
     * @param object $dataTypeContent
     * @param object $options
     * @return void
     */
    public function createContent($row, $dataType, $dataTypeContent, $options) // phpcs:ignore Squiz.Commenting.FunctionComment.TypeHintMissing
    {
        $options = collect(Order::STATUSES)->keys()
            ->filter(function ($key) use ($row) {
                if ($row->field === 'merch_status' && $key === Order::STATUS_COMPLETED) {
                    return false;
                } elseif ($row->field === 'club_status' && $key === Order::STATUS_SHIPPED) {
                    return false;
                }

                return true;
            })
            ->reduce(function ($acc, $key) {
                $acc->{$key} = Order::STATUSES[$key];

                return $acc;
            }, (object)[ null => 'n/a' ]);

        return view('voyager::formfields.order_status', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'options' => $options,
        ]);
    }
}
