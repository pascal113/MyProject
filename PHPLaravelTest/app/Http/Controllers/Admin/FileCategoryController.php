<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\FileCategory;
use Illuminate\Http\Request;

class FileCategoryController extends AdminController
{
    /**
     * BRE(A)D: "Add"->store a single Category
     *
     * @param Request $request Http request.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        $currentMax = FileCategory::orderBy('order', 'desc')->limit(1)->first();

        $request->merge(['order' => ($currentMax->order ?? 0) + 1]);

        return parent::store($request);
    }

    /**
     * BR(E)AD: "Edit"->update a single Category
     *
     * @param Request $request Http request.
     * @param integer $id      ID of Order.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        return parent::update($request, $id);
    }
}
