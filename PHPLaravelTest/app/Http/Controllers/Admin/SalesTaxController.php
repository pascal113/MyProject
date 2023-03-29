<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Setting;
use Validator;

class SalesTaxController extends AdminController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if (!$menuItem = MenuItem::where('route', '=', 'voyager.sales-tax.index')->first()) {
            abort(500, 'Could not load Sales Tax icon.');
        }

        $this->icon_class = $menuItem->icon_class;
    }

    /**
     * Display index
     */
    public function index(Request $request)
    {
        $value = Voyager::setting('sales-tax.global');

        return view('voyager::sales-tax.index')->with([
            'icon_class' => $this->icon_class,
            'value' => $value,
        ]);
    }

    /**
     * BR(E)AD: "Edit"->update global sales tax
     */
    public function update(Request $request, $id = null) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
    {
        Validator::make($request->all(), [
            'sales_tax' => ['required', 'numeric', 'between:0,100'],
        ])->validate();

        $settingKey = 'sales-tax.global';
        $setting = Setting::where('key', '=', $settingKey)->firstOrFail();

        $setting->value = $request->get('sales_tax');
        $setting->save();

        return redirect()
            ->route("voyager.sales-tax.index")
            ->with([
                'message' => __('voyager::generic.successfully_updated')." Sales Tax",
                'alert-type' => 'success',
            ]);
    }
}
