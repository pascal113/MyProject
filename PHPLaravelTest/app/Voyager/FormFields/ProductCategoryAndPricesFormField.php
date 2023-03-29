<?php

namespace App\Voyager\FormFields;

use App\Models\ProductCategory;
use TCG\Voyager\FormFields\AbstractHandler;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class ProductCategoryAndPricesFormField extends AbstractHandler
{
    /**
     * Name of the form field type as visible to admin creating when a BREAD
     *
     * @var string
     */
    protected $codename = 'product-category-and-prices';

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
        $allCategories = ProductCategory::orderBy('name')->get();

        $dataType = DataType::where('slug', 'products')->firstOrFail();

        $dataRows = collect(['product_category_id', 'num_washes'])
            ->map(function ($field) use ($dataType) {
                return DataRow::where('data_type_id', $dataType->id)
                    ->where('field', $field)
                    ->firstOrFail();
            });

        return view('voyager::formfields.product-category-and-prices', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'allCategories' => $allCategories,
            'labels' => [
                'category' => $dataRows->firstWhere('field', 'product_category_id')->display_name,
                'price' => 'Price',
                'numWashes' => $dataRows->firstWhere('field', 'num_washes')->display_name,
                'priceMonthly' => 'Price Monthly',
                'priceYearly' => 'Price Yearly / One Year',
            ],
        ]);
    }
}
