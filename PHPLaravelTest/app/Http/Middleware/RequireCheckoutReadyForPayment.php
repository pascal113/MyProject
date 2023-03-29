<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RequireCheckoutReadyForPayment
{
    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Cart::hasWashClubProducts()) {
            // Require a user and a home location selection
            if (!(Auth::user()->home_location ?? null)) {
                return redirect()->route('checkout.memberships.home-car-wash.edit');
            }

            // Require vehicle, membership, or reactivation selection for each wash club
            foreach (Cart::getWashClubInstances()->keys() as $index) {
                if (!Session::get('checkout.memberships.'.$index.'.options.is_gift', null)
                    && !Session::get('checkout.memberships.'.$index.'.options.vehicle_id', null)
                    && !Session::get('checkout.memberships.'.$index.'.options.modifies_membership_wash_connect_id', null)
                    && !Session::get('checkout.memberships.'.$index.'.options.reactivates_membership_wash_connect_id', null)
                ) {
                    return redirect()->route('checkout.memberships.details.edit', [ 'index' => $index ]);
                }
            }
        }

        if (Cart::hasPhysicalProducts()) {
            foreach ([
                'first_name',
                'last_name',
                'address',
                'city',
                'state',
                'zip',
                'phone',
            ] as $key) {
                if (!(Auth::user() ? Auth::user()->shipping->{$key} : Session::get('user.shipping.'.$key))) {
                    return redirect()->route('checkout.shipping');
                }
            }
        }

        return $next($request);
    }
}
