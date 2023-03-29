<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Models\Coupon;
use App\Models\Product;
use FPCS\FlexiblePageCms\Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Cart page
     *
     * @param Request $request Http request.
     */
    public function index(Request $request)
    {
        Cart::updateAndSave();

        $askAQuestion = CmsPage::getContentFromPage('/', 'askAQuestion');

        $discountFloat = Cart::discountFloat();
        $preDiscountSubTotal = Cart::preDiscountSubTotalFloat();
        $rows = Cart::serializableContent();
        $shippingPrice = Cart::shippingPriceFloat();
        $subTotalFloat = Cart::subTotalFloat();

        return parent::view('cart.index', compact('askAQuestion', 'discountFloat', 'preDiscountSubTotal', 'rows', 'shippingPrice', 'subTotalFloat'));
    }

    /**
     * Add a product to cart
     *
     * @param Request $request Http request.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function addProduct(Request $request)
    {
        $product = Product::findOrFail($request->get('productId'));

        $options = $request->input('options', []);
        if ($options['variant_id'] ?? null) {
            $options['variant_name'] = $product->variants->find($options['variant_id'])->name ?? null;
        }

        $qty = (int)$request->get('qty', 1);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->get('qty'),
            'price' => $product->getPriceEach($product->variants->find($options['variant_id'] ?? null) ?? null, $qty),
            'weight' => 0,
            'options' => $options,
        ])
            ->associate(Product::class);

        Cart::updateAndSave();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'content' => Cart::serializableContent(),
                    'count' => Cart::count(),
                ],
            ]);
        }

        return Redirect::back();
    }

    /**
     * Update the qty of a row in the cart
     */
    public function updateRow(Request $request): JsonResponse
    {
        if (!$cartItem = Cart::getRow($request->get('rowId'))) {
            abort(404);
        }

        $index = Cart::getWashClubProductIndex($cartItem);
        if ($index !== null) {
            Session::forget('checkout.memberships.'.$index);
        }

        $product = Product::findOrFail($cartItem->id);

        Cart::update($request->get('rowId'), [
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->get('qty'),
            'price' => $product->getPriceEach($product->variants->find($cartItem->options['variant_id'] ?? null) ?? null, (int)$request->get('qty', 1)),
            'weight' => 0,
            'options' => $cartItem->options,
        ]);

        $changes = Cart::updateAndSave();

        return Response::json([
            'success' => true,
            'data' => [
                'content' => Cart::serializableContent(),
                'couponsRemoved' => $changes->couponsRemoved,
                'preDiscountSubTotal' => Cart::preDiscountSubTotalFloat(),
                'shippingPrice' => Cart::shippingPriceFloat(),
                'discount' => Cart::discountFloat(),
                'subTotal' => Cart::subTotalFloat(),
            ],
        ])->setStatusCode(200);
    }

    /**
     * Remove a row in the cart
     */
    public function removeRow(Request $request): JsonResponse
    {
        $cartItem = Cart::getRow($request->get('rowId'));

        $index = $cartItem ? Cart::getWashClubProductIndex($cartItem) : null;
        if ($index !== null) {
            Session::forget('checkout.memberships.'.$index);
        }

        Cart::remove($request->get('rowId'));

        $changes = Cart::updateAndSave();

        return Response::json([
            'success' => true,
            'data' => [ 'couponsRemoved' => $changes->couponsRemoved ],
        ])->setStatusCode(200);
    }

    /**
     * Add a coupon to cart
     *
     * @param Request $request Http request.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function addCoupon(Request $request)
    {
        $coupons = Coupon::getByCode($request->get('code'));

        if (!$coupons->count()) {
            return self::abort(404, [ 'message' => 'The code you entered is invalid.' ]);
        }

        $coupons = $coupons->filter(function ($coupon) {
            return !$coupon->is_expired;
        });
        if (!$coupons->count()) {
            return self::abort(404, [ 'message' => 'That coupon has expired.' ]);
        }

        $coupons = $coupons->filter(function ($coupon) {
            $isApplicable = Cart::content()->first(function ($item) use ($coupon) {
                return $coupon->isApplicableTo($item);
            });

            return $isApplicable;
        });
        if (!$coupons->count()) {
            return self::abort(400, [ 'message' => 'That coupon does not apply to any of your products or is expired.' ]);
        }

        $minimumCartTotal = max($coupons->pluck('minimum_cart_total')->toArray());
        $coupons = $coupons->filter(function ($coupon) {
            $meetsMinimum = Cart::preDiscountSubTotal() >= $coupon->minimum_cart_total;

            return $meetsMinimum;
        });
        if (!$coupons->count()) {
            return self::abort(400, [ 'message' => 'You must have at least $'.number_format($minimumCartTotal, 0).' worth of products in your cart in order to use that coupon.' ]);
        }

        $coupons = $coupons->filter(function ($coupon) {
            $isUsed = Cart::content()->first(function ($item) use ($coupon) {
                return isset($item->options['couponId']) && $item->options['couponId'] === $coupon->id;
            });

            return !$isUsed;
        });
        if (!$coupons->count()) {
            return self::abort(400, [ 'message' => 'That coupon has already been added to your cart.' ]);
        }

        $coupons->each(function ($coupon) {
            foreach (Cart::content() as $item) {
                if ($coupon->isApplicableTo($item)) {
                    $options = $item->options;
                    $options['couponId'] = $coupon->id;
                    $options['couponCode'] = $coupon->code;

                    Cart::update($item->rowId, [ 'options' => $options ]);
                }
            }
        });

        Cart::updateAndSave();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'content' => Cart::serializableContent(),
                    'count' => Cart::count(),
                    'preDiscountSubTotal' => Cart::preDiscountSubTotalFloat(),
                    'discount' => Cart::discountFloat(),
                    'shippingPrice' => Cart::shippingPriceFloat(),
                    'subTotal' => Cart::subTotalFloat(),
                    'tax' => Cart::taxFloat(),
                    'total' => Cart::totalFloat(),
                ],
            ]);
        }

        return Redirect::back();
    }

    /**
     * Remove a coupon from the cart
     *
     * @param Request $request Http request.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function removeCoupon(Request $request)
    {
        $id = $request->get('id');

        $couponFound = Cart::removeCouponById($id);

        if (!$couponFound) {
            self::abort(400, ['message' => 'That coupon has not been added to your cart.']);
        }

        Cart::updateAndSave();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'content' => Cart::serializableContent(),
                    'count' => Cart::count(),
                    'preDiscountSubTotal' => Cart::preDiscountSubTotalFloat(),
                    'discount' => Cart::discountFloat(),
                    'shippingPrice' => Cart::shippingPriceFloat(),
                    'subTotal' => Cart::subTotalFloat(),
                    'tax' => Cart::taxFloat(),
                    'total' => Cart::totalFloat(),
                ],
            ]);
        }

        return Redirect::back();
    }
}
