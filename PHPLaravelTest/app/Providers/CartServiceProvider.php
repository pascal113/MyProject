<?php

namespace App\Providers;

use App\Models\Coupon;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\GatewayService;
use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use TCG\Voyager\Facades\Voyager;

class CartServiceProvider extends Cart
{
    /**
     * Return a single CartItem from the Cart, by rowId
     */
    public function getRow(string $rowId): ?CartItem
    {
        return $this->content()->first(function (CartItem $row) use ($rowId) {
            return $row->rowId === $rowId;
        }) ?? null;
    }

    /**
     * Returns bool indicating whether Cart contains any Products that are eligible for Digital Delivery
     */
    public function hasDigitalProducts(): bool
    {
        return !!$this->content()->some(function ($item) {
            $product = Product::find($item->id);

            return $product->is_digital ?? false;
        });
    }

    /**
     * Returns bool indicating whether Cart contains any Products that require physical shipping
     */
    public function hasPhysicalProducts(): bool
    {
        return !!$this->content()->some(function ($item) {
            $product = Product::find($item->id);

            return $product->is_physical ?? false;
        });
    }

    /**
     * Checks if there are any recurring products in the cart
     */
    public function hasRecurringProducts(): bool
    {
        return $this->content()->some(function ($item) {
            return $this->isItemRecurringProduct($item);
        });
    }

    /**
     * Returns true if the passed cart item is recurring
     */
    public function isItemRecurringProduct(CartItem $item): bool
    {
        if (!$item->model->variants) {
            return false;
        }

        return $item->model->variants->firstWhere('id', $item->options->variant_id)->is_recurring ?? false;
    }

    /**
     * Checks if there are any wash club membership products in the cart
     */
    public function hasWashClubProducts(): bool
    {
        return $this->content()->some(function ($item) {
            return $this->isItemWashClubProduct($item);
        });
    }

    /**
     * Checks if there are any wash club membership products in the cart that are not gifts
     */
    public function hasNonGiftWashClubProducts(): bool
    {
        return $this->content()->some(function ($item) {
            if (!$this->isItemWashClubProduct($item)) {
                return false;
            }

            $index = $this->getWashClubProductIndex($item);

            return !(Session::get('checkout.memberships.'.$index.'.options.is_gift', false));
        });
    }

    /**
     * Returns the index (used in session vars and route urls) of the passed wash club product cart item
     */
    public function getWashClubProductIndex(CartItem $item): ?int
    {
        if (!$this->isItemWashClubProduct($item)) {
            return null;
        }

        $foundIndex = null;
        foreach ($this->getWashClubInstances()->values() as $index => $cartItem) {
            if ($cartItem->rowId === $item->rowId) {
                $foundIndex = $index;
            }
        }

        return $foundIndex;
    }

    /**
     * Returns true if the passed cart item can be bought as a gift
     */
    public function isItemGiftable(CartItem $item): bool
    {
        return !$this->isItemRecurringProduct($item) && !($item->options['modifies_membership_wash_connect_id'] ?? null) && !($item->options['reactivates_membership_wash_connect_id'] ?? null);
    }

    /**
     * Returns true if the passed cart item is a Wash Club
     */
    public function isItemWashClubProduct(CartItem $item): bool
    {
        $product = Product::find($item->id);

        return $product->is_wash_club ?? false;
    }

    /**
     * Return the CartItem that belongs to the wash club product at $index
     */
    public function getWashClubProductByIndex(int $index): CartItem
    {
        return $this->getWashClubInstances()->values()->get($index);
    }

    /**
     * Returns all wash club membership products in the cart
     * This is different from getWashClubCartItems() as illustrated by the example of a cart containing 2x Wash Club A and 1x Wash Club B:
     *   getWashClubInstances() returns [ { Wash Club A One }, { Wash Club A Two }, { Wash Club B } ]
     *   getWashClubCartItems() returns [ { Wash Club A, qty = 2 }, { Wash Club B, qty = 1 } ]
     */
    public function getWashClubInstances(): Collection
    {
        $cartItems = $this->getWashClubCartItems();

        $instances = collect($cartItems)->reduce(function ($acc, $cartItem) {
            for ($x = 0; $x < $cartItem->qty; $x++) {
                $newCartItem = clone $cartItem;
                $newCartItem->name = $this->getWashClubNameWithTerm($cartItem).($cartItem->qty > 1 ? ' '.ucwords(self::intToWord($x + 1)) : '');

                $acc->push($newCartItem);
            }

            return $acc;
        }, collect());

        return $instances;
    }

    /**
     * Convert an int to english words
     */
    private static function intToWord(int $int): string
    {
        if ($int >= 100) {
            return $int;
        }

        $digitOne = [ 'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $digitTwo = [ null, 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety' ];
        $exceptions = [ 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen' ];

        $output = $int < 0 ? 'negative ' : '';

        if (isset($exceptions[$int])) {
            return $output.$exceptions[$int];
        }

        if (abs($int) < 10) {
            return $output.$digitOne[$int];
        }

        return $output.$digitTwo[substr($int, 0, 1)].(substr($int, 1, 1) > 0 ? '-'.$digitOne[substr($int, 1, 1)] : '');
    }

    /**
     * Returns all wash club membership items in the cart
     */
    public function getWashClubCartItems(): Collection
    {
        return $this->content()->filter(function ($item) {
            $product = Product::find($item->id);

            return $product->is_wash_club;
        });
    }

    /**
     * Return full name of wash club including term
     */
    public function getWashClubNameWithTerm(CartItem $item): string
    {
        $variant = ProductVariant::find($item->options['variant_id'] ?? null);

        return $item->model->getNameWithTerm($item->model, $variant);
    }

    /**
     * Run all updates and save cart
     */
    public function updateAndSave(): object
    {
        $this->updateTaxes();

        $changes = $this->updateDiscounts();

        $this->save();

        return (object)[
            'couponsRemoved' => $changes->couponsRemoved,
        ];
    }

    /**
     * Update discounts for all cart items, including automatically removing
     * any coupons that are invalid/expired/minimum-requirement-not-met
     */
    public function updateDiscounts(): object
    {
        $couponsRemoved = $this->removeInvalidCoupons();

        foreach ($this->content() as $item) {
            if (!($item->options['couponId'] ?? null)) {
                continue;
            }

            if ($coupon = Coupon::find($item->options['couponId'])) {
                $this->setDiscount($item->rowId, $coupon->percent_discount);
            }
        }

        return (object)[
            'couponsRemoved' => $couponsRemoved,
        ];
    }

    /**
     * Set the taxRate on all Wash Club products, given a Location
     */
    public function updateTaxesForWashClubProducts(?Location $location = null): void
    {
        foreach ($this->getWashClubCartItems() as $cartItem) {
            $productVariant = ProductVariant::find($cartItem->options->variant_id);

            if ($location && $productVariant) {
                $resp = GatewayService::get('v2/membership-purchases/tax/'.$location->wash_connect_id.'/'.$productVariant->wash_connect_id);

                $taxRate = $resp ? $resp->data / $productVariant->price * 100 : null;
            } else {
                $taxRate = null;
            }

            $this->setTax($cartItem->rowId, $taxRate);
        }

        $this->updateTaxes();
    }

    /**
     * Set the taxRate on all products
     */
    public function updateTaxes(): void
    {
        $allWashClubsHaveTax = $this->getWashClubCartItems()->every(function ($cartItem) {
            return $cartItem->taxRate !== null;
        });

        $this->content()->map(function ($cartItem) use ($allWashClubsHaveTax) {
            if ($this->isItemWashClubProduct($cartItem)) {
                return;
            }

            $taxRate = $allWashClubsHaveTax ? (float)Voyager::setting('sales-tax.global') : null;

            $this->setTax($cartItem->rowId, $taxRate);
        });
    }

    /**
     * Is tax calculated for all items in cart?
     */
    public function isTaxFullyCalculated(): bool
    {
        return collect($this->content())->every(function ($cartItem) {
            return $cartItem->taxRate !== null;
        });
    }

    /**
     * Save cart to database if user is logged in
     */
    public function save(): void
    {
        if (!Auth::user()) {
            return;
        }

        $this->deleteSaved();

        $this->store(self::dbIdentifier());
    }

    /**
     * Delete saveds cart from database
     */
    public function deleteSaved(): void
    {
        if (!Auth::user()) {
            return;
        }

        DB::table(config('cart.database.table'))->where('identifier', '=', self::dbIdentifier())->delete();
    }

    /**
     * Return string identifier for storing carts to database
     */
    private static function dbIdentifier(): ?string
    {
        if (!Auth::user()) {
            return null;
        }

        return 'userId:'.Auth::user()->id.':default';
    }

    /**
     * Empty cart and forget all cart-related stuffs in session
     */
    public function destroy(): void
    {
        Session::forget('checkout');

        parent::destroy();
    }

    /**
     * Return cart content
     */
    public function content(): Collection
    {
        return parent::content()->map(function ($row) {
            $row->preDiscountPrice = $row->model->getPriceEach(null, 1);

            return $row;
        });
    }

    /**
     * Return serializable cart content
     *
     * Cart::content() returns a Collection of CartItem objects.
     *   The CartItem object is restricted in regards what values can be stored on it.
     *     When serialized to JSON (such as when passing to a Blade view using @json, or returning in an API endpoint response), some values are lost on CartItem objects.
     *   This method returns a Collection of stdClass objects, which will retain all of those values when serialized.
     */
    public function serializableContent(): object
    {
        return $this->content()->reduce(function ($acc, $row) {
            // Stash values to vars
            $preDiscountPrice = $row->preDiscountPrice;
            $total = $row->total;
            $associatedModel = $row->associatedModel;
            $discountRate = $row->discountRate;

            // Convert CartItem to stdClass
            $row = (object)$row->toArray();

            // Add stashed values back
            $row->associatedModel = $associatedModel;
            $row->discountRate = $discountRate;
            $row->preDiscountPrice = $preDiscountPrice;
            $row->total = $total;

            $acc->{$row->rowId} = $row;

            return $acc;
        }, (object)[]);
    }

    /**
     * Return the pre-discount sub total as a human-friendly string
     */
    public function preDiscountSubTotal($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->preDiscountSubTotalFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the pre-discount sub total
     */
    public function preDiscountSubTotalFloat(): float
    {
        $preDiscountSubTotal = $this->content()->reduce(function ($acc, $row) {
            $productVariant = $row->options->variant_id ? ProductVariant::find($row->options->variant_id) : null;
            if ($row->preDiscountPrice && $row->preDiscountPrice > $row->price) {
                $price = $row->preDiscountPrice * $row->qty;
            } else {
                $price = $row->model->getPrice($productVariant, $row->qty);
            }
            return $acc + $price;
        }, 0);

        return $preDiscountSubTotal;
    }

    /**
     * Return the volume pricing discount as a human-friendly string
     */
    public function volumeDiscount($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->volumeDiscountFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the volume pricing discount
     */
    public function volumeDiscountFloat(): float
    {
        return $this->content()->reduce(function ($acc, $row) {
            $productVariant = $row->options->variant_id ? ProductVariant::find($row->options->variant_id) : null;
            $discount = 0;
            if ($row->preDiscountPrice && $row->preDiscountPrice > $row->price) {
                $discount = $row->preDiscountPrice * $row->qty - $row->model->getPrice($productVariant, $row->qty);
            }
            return $acc + $discount;
        }, 0);
    }

    /**
     * Return the total discount as a human-friendly string
     */
    public function discount($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->discountFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the total discount
     *
     * parent::discountFloat() returns the COUPON-based discount
     * $this->volumeDiscountFloat() returns the VOLUME PRICING-based discount
     */
    public function discountFloat(): float
    {
        return parent::discountFloat() + $this->volumeDiscountFloat();
    }

    /**
     * Return the sub total as a human-friendly string
     */
    public function subTotal($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->subTotalFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the sub total
     */
    public function subTotalFloat(): float
    {
        return parent::subtotalFloat();
    }

    /**
     * Return the total shipping price as a human-friendly string
     */
    public function shippingPrice($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->shippingPriceFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the total shipping price
     */
    public function shippingPriceFloat(): float
    {
        $shippingPrice = $this->content()->reduce(function ($shippingPrice, $row) {
            $product = Product::findOrFail($row->id);

            $shippingPrice += $product->getShippingPriceByQuantity($row->qty);

            return $shippingPrice;
        }, 0);

        return $shippingPrice;
    }

    /**
     * Return the tax as a human-friendly string
     */
    public function tax($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->taxFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the tax
     */
    public function taxFloat(): float
    {
        $taxWithoutShipping = parent::taxFloat();

        $taxForShipping = $this->isTaxFullyCalculated() ? $this->shippingTaxFloat() : 0;

        return $taxWithoutShipping + $taxForShipping;
    }

    /**
     * Calculate the tax on shipping costs
     */
    public function shippingTaxFloat(): float
    {
        $taxRate = (float)Voyager::setting('sales-tax.global');

        return $this->shippingPriceFloat() * $taxRate / 100;
    }

    /**
     * Return the total as a human-friendly string
     */
    public function total($decimals = Order::DECIMALS, $decimalPoint = Order::DECIMAL_POINT, $thousandSeparator = Order::THOUSAND_SEPARATOR): string
    {
        return number_format($this->totalFloat(), $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Calculate the total
     */
    public function totalFloat(): float
    {
        return $this->subTotalFloat() + $this->shippingPriceFloat() + $this->taxFloat();
    }

    /**
     * This method will loop through cart check if there are coupons applied that did not met requirement
     * If coupon did not met requirement it will be removed from cart
     */
    public function removeInvalidCoupons(): array
    {
        $couponIds = [];
        // we want to fetch all of the applied coupons from rows
        foreach ($this->content() as $row) {
            if (isset($row->options['couponId'])) {
                $couponIds[] = $row->options['couponId'];
            }
        }

        $couponsRemoved = [];

        // here we want to check if each coupon meets minimum cart total requirements if not they are ready to be removed
        foreach (array_unique($couponIds) as $couponId) {
            $coupon = Coupon::find($couponId);

            if (!$coupon || ($coupon->is_expired || $this->preDiscountSubTotal() < $coupon->minimum_cart_total)) {
                if ($this->removeCouponById($coupon->id)) {
                    $couponsRemoved[] = $coupon->code;
                }
            }
        }

        return $couponsRemoved;
    }

    /**
     * Removes coupon from the cart
     */
    public function removeCouponById(string $couponId): bool
    {
        $couponRemoved = false;
        foreach ($this->content() as $row) {
            if ($row->options['couponId'] ?? null === $couponId) {
                $this->setDiscount($row->rowId, 0);
                $options = $row->options;
                unset($options['couponId']);
                unset($options['couponCode']);
                $this->update($row->rowId, ['options' => $options]);

                $couponRemoved = true;
            }
        }

        return $couponRemoved;
    }
}
