<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevOnly
{
    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only allow on local and dev
        if (!in_array(app()->env, ['local', 'dev'])) {
            abort(404);
        }

        return $next($request);
    }
}
