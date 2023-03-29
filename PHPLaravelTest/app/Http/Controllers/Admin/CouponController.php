<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class CouponController extends AdminController
{
    /**
     * BR(E)AD: "Edit"->update a single Location
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataType = self::removeProductsRelationshipRow($dataType, 'edit');

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope !== '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        self::attachProducts($request, $data);

        event(new BreadDataUpdated($dataType, $data));

        return redirect()
            ->route("voyager.{$dataType->slug}.edit", $id)
            ->with([
                'message' => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
    }

    /**
     * BRE(A)D: "Add"->store a single Location
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));
        $dataType = self::removeProductsRelationshipRow($dataType, 'add');

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        self::attachProducts($request, $data);

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            return redirect()
                ->route("voyager.{$dataType->slug}.edit", $data->id)
                ->with([
                    'message' => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Remove products relationship editRow or addRow
     */
    private static function removeProductsRelationshipRow(DataType $dataType, string $type): DataType
    {
        $colName = $type.'Rows';

        $dataType->{$colName} = $dataType->{$colName}->reduce(function ($acc, $row) {
            if ($row->field !== 'coupon_belongstomany_product_relationship') {
                $acc->push($row);
            }

            return $acc;
        }, new Collection());

        return $dataType;
    }

    /**
     * Detach all current products, and attach new
     */
    private static function attachProducts(Request $request, Coupon $data): void
    {
        if ($request->get('coupon_products_all') === 'on') {
            $all = Coupon::getAllAvailableProductsAndProductVariants();

            $relations = collect($all->reduce(function ($acc, $item) {
                if ($item instanceof ProductVariant) {
                    array_push($acc, (object)[
                        'product_id' => $item->product->id,
                        'product_variant_id' => $item->id,
                    ]);
                } elseif ($item instanceof Product) {
                    array_push($acc, (object)[
                        'product_id' => $item->id,
                        'product_variant_id' => null,
                    ]);
                }

                return $acc;
            }, []));
        } else {
            $regexp = '/^product\.([0-9]+)(\.variant\.([0-9]+))?$/';
            $relations = collect($request->get('coupon_belongstomany_product_relationship', []))
                ->map(function ($item) use ($regexp) {
                    return (object)[
                        'product_id' => (int)preg_replace($regexp, '$1', $item),
                        'product_variant_id' => preg_replace($regexp, '$3', $item) ? (int)preg_replace($regexp, '$3', $item) : null,
                    ];
                })
                ->toArray();
        }

        $data->products()->detach();
        foreach ($relations as $relation) {
            $data->products()->attach($relation->product_id, [ 'product_variant_id' => $relation->product_variant_id ]);
        }
    }

    /**
     * Get BREAD relations data.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function relation(Request $request)
    {
        $parent = parent::relation($request);

        if ($request->get('type') !== 'coupon_belongstomany_product_relationship') {
            return $parent;
        }

        extract((array)$parent->getData());

        $results = Coupon::getProductAndProductVariantOptions()
            ->filter(function ($option) use ($request) {
                return !$request->get('search') || strpos(strtolower($option->text), strtolower($request->get('search'))) !== false;
            })
            ->values();

        return response()->json([
            'results' => $results,
            'pagination' => $pagination,
        ]);
    }
}
