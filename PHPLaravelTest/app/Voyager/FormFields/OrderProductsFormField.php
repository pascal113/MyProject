<?php

namespace App\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class OrderProductsFormField extends AbstractHandler
{
    /**
     * Name of the form field type as visible to admin creating when a BREAD
     *
     * @var string
     */
    protected $codename = 'order_products';

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
        return view('voyager::formfields.order_products', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
