<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class ProductController extends AdminController
{
    /**
     * BRE(A)D: "Add" a single Product
     */
    public function create(Request $request)
    {
        $parent = parent::create($request);
        extract($parent->getData());

        $dataType = self::setRows($dataType, null, 'add');

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$dataType->slug.edit-add")) {
            $view = "voyager::$dataType->slug.edit-add";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * BRE(A)D: "Add"->store a single Product
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $dataType = self::removeProductShippingPricesRelationshipRow($dataType, 'add');

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->addRows)->validate();

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        // Save prices
        foreach ($request->get('prices') ?? [] as $price) {
            $data->prices()->create($price);
        }

        // Save shipping prices
        foreach ($request->get('product_shipping_prices') ?? [] as $shippingPrice) {
            $data->shipping_prices()->create($shippingPrice);
        }

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message' => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * BR(E)AD: "Edit" a single Product
     */
    public function edit(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $parent = parent::edit($request, $id);
        extract($parent->getData());

        $product = Product::with('shipping_prices')->findOrFail($id);

        $dataType = self::setRows($dataType, $product->category, 'edit');

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$dataType->slug.edit-add")) {
            $view = "voyager::$dataType->slug.edit-add";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * BR(E)AD: "Edit"->update a product
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Order.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $dataType = self::removeProductShippingPricesRelationshipRow($dataType, 'edit');

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope !== '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }
        $data->shipping_prices();

        // Check permission
        $this->authorize('edit', $data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Upsert variant prices
        foreach ([ 'Monthly', 'Yearly', 'One Year' ] as $variantName) {
            if (!$price = $request->get($variantName === 'Monthly' ? 'price_monthly' : 'price_yearly')) {
                if ($variant = $data->variants->firstWhere('name', $variantName)) {
                    $variant->delete();
                }

                continue;
            }

            $variant = $data->variants->firstWhere('name', $variantName) ?? new ProductVariant([
                'product_id' => $data->id,
                'name' => $variantName,
                'is_recurring' => $variantName === 'Monthly' || $variantName === 'Yearly',
            ]);
            $variant->price = $price;
            $variant->save();
        }
        $data->updated_at = \Carbon\Carbon::now();
        $data->save();

        // Update prices
        $data->prices()->delete();
        foreach ($request->get('prices') ?? [] as $price) {
            $data->prices()->create($price);
        }

        // Update shipping prices
        $data->shipping_prices()->delete();
        foreach ($request->get('product_shipping_prices') ?? [] as $shippingPrice) {
            $data->shipping_prices()->create($shippingPrice);
        }

        if (auth()->user()->can('browse', $model)) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    /**
     * Set the correct pricing rows for editRow or addRow
     */
    private static function setRows(DataType $dataType, ?ProductCategory $category = null, string $type): DataType
    {
        $colName = $type.'Rows';

        // Remove rows that are covered by the product-category-and-prices formfield
        $dataType->{$colName} = $dataType->{$colName}->reject(function ($row) {
            return in_array($row->field, ['product_category_id', 'num_washes']);
        });

        return $dataType;
    }

    /**
     * Remove services relationship editRow or addRow
     */
    private static function removeProductShippingPricesRelationshipRow(DataType $dataType, string $type): DataType
    {
        $colName = $type.'Rows';

        $dataType->{$colName} = $dataType->{$colName}->reduce(function ($acc, $row) {
            if ($row->field !== 'product_belongstomany_product_shipping_prices_relationship') {
                $acc->push($row);
            }

            return $acc;
        }, new Collection());

        return $dataType;
    }
}
