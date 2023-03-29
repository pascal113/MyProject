<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Files
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $file = File::find($request->id);
        if (!$file) {
            abort(404);
        }
        if ($file->category->is_public) {
            return $next($request);
        } elseif (!$file->category->is_public && Auth::user()
            && Auth::user()->hasPermission('frontend_company_files')) {
            return $next($request);
        }

        abort(403);
    }
}
