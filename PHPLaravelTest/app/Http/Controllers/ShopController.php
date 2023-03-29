<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Product;
use App\Services\OnScreenNotificationService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Modify an existing Membership
     */
    public function showModifyMembership(Request $request, string $washConnectId)
    {
        $membership = Membership::getForCurrentUserById($washConnectId);

        if ($membership->pending_modification && !$membership->pending_modification->is_cancelable) {
            return redirect()
                ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
                ->with(OnScreenNotificationService::with([
                    'message' => 'This membership already has a non-cancelable modification pending. You may not change or cancel it at this time.',
                    'level' => 'warning',
                ]));
        }

        $variantsAvailableForModifyingTo = $membership->variantsAvailableForModifyingTo();

        // Show only BIP options if current Membership is BIP
        if ($membership->club->is_bip ?? null) {
            $variantsAvailableForModifyingTo = $variantsAvailableForModifyingTo
                ->filter(function ($variant) use ($membership) {
                    return $variant->is_bip && $membership->canBeModifiedTo($variant);
                });
        }

        $allMembershipProducts = Product::whereHas('category', function ($query) {
            $query->where('slug', 'memberships');
        })->get();

        $productsAvailableForModifyingTo = $allMembershipProducts
            ->map(function ($product) use ($variantsAvailableForModifyingTo) {
                $product->variants = $variantsAvailableForModifyingTo->where('product_id', $product->id);

                return $product;
            })
            ->filter(function ($product) {
                return !!$product->variants->count();
            });

        return parent::view('shop.modify-membership', compact(
            'membership',
            'productsAvailableForModifyingTo',
        ));
    }
}
