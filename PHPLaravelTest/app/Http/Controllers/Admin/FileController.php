<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\File;
use App\Models\FileCategory;
use App\Services\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class FileController extends AdminController
{
    /**
     * BRE(A)D: "Add"->store a single File
     *
     * @param Request $request Http request.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        if ($request->get('type') === 'path') {
            Validator::make($request->all(), [
                'path' => ['required', 'url'],
            ])->validate();
        }

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->addRows)->validate();
        if (!$request->get('file_category_id')) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Please select a category.',
                    'alert-type' => 'error',
                ]);
        }

        // Set `order`
        $currentMax = File::where('file_category_id', $request->get('file_category_id'))
            ->orderBy('order', 'desc')
            ->limit(1)
            ->first();
        $request->merge(['order' => ($currentMax->order ?? 0) + 1]);

        // Save file from upload or URL
        $request = self::processFile($request);
        $data = new $dataType->model_name();
        $data->path = $request->get('path');

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

        event(new BreadDataAdded($dataType, $data));

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
     * BR(E)AD: "Edit"->update a single File
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Order.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        if ($request->get('type') === 'path') {
            Validator::make($request->all(), [
                'path' => ['required', 'url'],
            ])->validate();
        }

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

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

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        if (!$request->get('file_category_id')) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Please select a category.',
                    'alert-type' => 'error',
                ]);
        }

        // Save file from upload or URL
        $request = self::processFile($request, $data);
        $data->path = $request->get('path');

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

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
     * Save file that was uploaded or supplied by URL
     */
    private static function processFile(Request $request, ?File $previous = null): Request
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $contents = $file->get();

            $mimeType = $file->getMimeType();

            $fileName = $file->getClientOriginalName();

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailContents = $thumbnail->get();
                $thumbnailFileName = $thumbnail->getClientOriginalName();
            }
        } elseif ($request->has('path') && (!$previous || $previous->path !== $request->get('path'))) {
            if (preg_match('/(youtube|vimeo).com/i', $request->get('path'))) {
                $request->merge([
                    'mime_type' => 'external_url',
                    'path' => $request->get('path'),
                ]);

                return $request;
            }

            list($contents, $fileName, $mimeType) = FileService::fetchExternalFileFromUrl($request->get('path'));
        }

        if (!isset($contents)) {
            return $request;
        }

        $fileName = FileService::put($contents, $fileName);
        if (isset($thumbnailFileName)) {
            $thumbnailFileName = FileService::put($thumbnailContents, $thumbnailFileName);
        }

        if ($previous && $previous->path) {
            FileService::delete($previous->path);
        }

        $request->merge([
            'mime_type' => $mimeType,
            'path' => $fileName,
        ]);

        if (isset($thumbnailFileName)) {
            $request->merge(['thumbnail' => $thumbnailFileName]);
        }

        return $request;
    }

    /**
     * Show order page - either select category or display reordering interface
     */
    public function order(Request $request, ?string $categoryId = null): View
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (!$categoryId) {
            $categories = FileCategory::allNotEmpty();

            return view('voyager::files.order-choose-category', compact('categories', 'dataType'));
        }

        $category = FileCategory::findOrFail($categoryId);
        $model = app($dataType->model_name);
        $results = $model->where('file_category_id', $categoryId)->orderBy($dataType->order_column, 'asc')->get();

        $display_column = $dataType->order_display_column;

        $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->whereField($display_column)->first();

        return view('voyager::files.order', compact('categoryId', 'category', 'dataType', 'results', 'display_column', 'dataRow'));
    }

    /**
     * Save the updated order to the database. Per-category
     */
    public function update_order(Request $request)
    {
        $slug = 'files';

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        $model = app($dataType->model_name);

        $order = json_decode($request->input('order'));

        $column = $dataType->order_column;
        foreach ($order as $key => $item) {
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $i = $model->withTrashed()->findOrFail($item->id);
            } else {
                $i = $model->findOrFail($item->id);
            }
            $i->$column = $key + 1;
            $i->save();
        }
    }

    /**
     * BREA(D): "Delete"->destroy one or many Files
     */
    public function destroy(Request $request, $inputId)
    {
        $ids = [];
        if (empty($inputId)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $inputId;
        }

        foreach ($ids as $id) {
            $file = File::findOrFail($id);

            FileService::delete($file->path);
        }

        $ret = parent::destroy($request, $inputId);

        return $ret;
    }
}
