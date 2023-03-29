<?php

namespace App\Http\Middleware;

use App\Services\GatewayService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOAuth
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            GatewayService::checkSessionExpiration();
        }

        return $next($request);
    }
}
