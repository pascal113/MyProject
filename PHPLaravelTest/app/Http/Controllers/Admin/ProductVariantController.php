<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class ProductVariantController extends AdminController
{
    /**
     * Run the `sync:wash-clubs` command immediately
     */
    public function syncWithWashConnect(): RedirectResponse
    {
        Artisan::call('sync:wash-clubs');

        return redirect()
            ->route('voyager.products.index')
            ->with([
                'message' => 'Wash Clubs have been synced to WashConnect data.',
                'alert-type' => 'success',
            ]);
    }
}
