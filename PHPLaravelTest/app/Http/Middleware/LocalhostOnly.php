<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalhostOnly
{
    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only allow requests from the same IP as this server is running on
        if (($request->server('SERVER_ADDR') ?? '127.0.0.1') !== $request->ip()) {
            abort(401);
        }

        return $next($request);
    }
}
