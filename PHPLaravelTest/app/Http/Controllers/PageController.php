<?php

namespace App\Http\Controllers;

use FPCS\FlexiblePageCms\Traits\Voyager\PublicPageController;

class PageController extends Controller
{
    use PublicPageController;

    /**
     * Show static Page
     * This function is intended so that static builds can happen before they are integrated with the CMS & database
     * This way, just a route and a view can be created, and then the route calls this method with said view.
     *
     * @param string $view Path+filename of Blade view file to render.
     * @return Illuminate\Support\Facades\View
     */
    public function showStatic(string $view)
    {
        if (!view()->exists($view)) {
            self::abort(404);
        }

        return parent::view($view);
    }
}
