<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\Cart;
use Closure;
use Illuminate\Http\Request;

class RequireNonEmptyCart
{
    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Require a non-empty cart
        $rows = Cart::content();
        if (!$rows->count()) {
            return redirect()->route('cart.index');
        }

        return $next($request);
    }
}
