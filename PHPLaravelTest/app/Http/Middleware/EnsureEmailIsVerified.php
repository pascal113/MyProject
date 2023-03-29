<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
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
        if (!Auth::user()) {
            return $next($request);
        }

        if (!(Auth::user()->email_verified_at ?? null)) {
            $routeName = $request->route()->action['as'] ?? null;
            $messageType = ($routeName === 'my-account.contact-info-shipping-address.edit' ? 'account-information' : null)
                ?? ($routeName === 'my-account.password.edit' ? 'account-information' : null)
                ?? ($routeName === 'checkout.payment-methods.edit' ? 'complete-transaction' : null)
                ?? ($routeName === 'checkout.payment-methods.storeNew' ? 'change-payment-method' : null)
                ?? ($routeName === 'my-account.payment-methods.edit' ? 'change-payment-method' : null)
                ?? '';

            return redirect()->to(route('email.verify', [
                'redirectTo' => '/'.$request->path(),
                'messageType' => $messageType,
            ]));
        }

        return $next($request);
    }
}
