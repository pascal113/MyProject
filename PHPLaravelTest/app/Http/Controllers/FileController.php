<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FileController extends Controller
{
    /**
     * Company Files Index
     */
    public function companyFilesIndex(Request $request)
    {
        $categories = FileCategory::allNotEmptyAndNotPublic();

        return parent::view('company-files.index', compact('categories'));
    }

    /**
     * Return file asset
     */
    public function file(string $id): RedirectResponse
    {
        $file = File::with('category')->findOrFail($id);
        $redirectLocation = $file->getFileLocation();

        if (!$file->category->is_public && !Auth::user()) {
            Redirect::back();
        }

        return Redirect::to($redirectLocation);
    }

    /**
     * Return file asset
     */
    public function thumbnail(string $id): RedirectResponse
    {
        $file = File::findOrFail($id);

        $redirectLocation = $file->temporaryThumbnailUrl;

        return Redirect::to($redirectLocation);
    }
}
