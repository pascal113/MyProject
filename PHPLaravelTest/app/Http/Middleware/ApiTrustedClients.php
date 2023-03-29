<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTrustedClients
{
    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestHost = gethostbyaddr($request->ip());

        $trustedClients = config('api.trusted_clients') ?? [];

        // Only allow requests from a hostname in the trusted clients list
        if (in_array('*', $trustedClients) || in_array($requestHost, $trustedClients)) {
            return $next($request);
        }

        abort(401);
    }
}
