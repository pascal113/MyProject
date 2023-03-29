<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class SplashController extends AdminController
{
    /**
     * BR(E)AD: "Edit"->update a single Location
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);

        if ($request->get('is_enabled')) {
            $model->where('is_enabled', 1)->update(['is_enabled' => 0]);
        }

        self::fixLinkUrlRequestData($request);

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
        $model = app($dataType->model_name);

        if ($request->get('is_enabled')) {
            $model->where('is_enabled', 1)->update(['is_enabled' => 0]);
        }

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        self::fixLinkUrlRequestData($request);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $model());

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
     * link_url uses a `url` component, which returns an object e.g. { value: 'http://someurl.com', openInNewTab: false }
     * We only want to use the ->value part for this
     */
    private static function fixLinkUrlRequestData(Request $request): void
    {
        // Fix link_url value
        $request->merge([ 'link_url' => $request->get('link_url') ? $request->get('link_url')['value'] : null ]);
    }
}
