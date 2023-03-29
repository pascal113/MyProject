<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Models\MembershipPurchase;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Order extends Model
{
    use SoftDeletes;

    public const DECIMALS = 2;
    public const DECIMAL_POINT = '.';
    public const THOUSAND_SEPARATOR = '';

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_email',
        'customer_first_name',
        'customer_last_name',
        'access_token',
        'discount',
        'sub_total',
        'tax_rate',
        'tax',
        'shipping_price',
        'total',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_phone',
        'merch_status',
        'club_status',
        'transaction_error',
        'transaction_id',
        'shipped_at',
        'shipping_notification_sent_at',
    ];

    /**
     * Default number of results to show per page when paginating
     *
     * @var integer
     */
    protected $perPage = 15;

    public const STATUS_UNPAID = '000-unpaid';
    public const STATUS_FAILED_PAYMENT = '010-failed-payment';
    public const STATUS_PENDING = '100-pending';
    public const STATUS_SHIPPED = '200-shipped'; // applies only to merch_status
    public const STATUS_COMPLETED = '201-completed'; // applies only to club_status
    public const STATUS_REFUNDED = '300-refunded';
    public const STATUSES = [
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_FAILED_PAYMENT => 'Failed Payment',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SHIPPED => 'Shipped',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_REFUNDED => 'Refunded',
    ];

    /**
     * Get user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get products
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'order_product')
            ->using(OrderProduct::class)
            ->withPivot([
                'id',
                'product_variant_id',
                'reactivates_wash_connect_id',
                'coupon_code',
                'purchase_price_ea',
                'num_washes_ea',
                'qty',
                'pre_discount_sub_total',
                'discount',
                'sub_total',
                'tax',
                'total',
                'created_at',
            ]);
    }

    /**
     * Returns bool indicating whether Order contains any Products that are eligible for Digital Delivery
     */
    public function hasDigitalProducts(): bool
    {
        return !!$this->products->some(function ($item) {
            $product = Product::find($item->id);

            return $product->is_digital ?? false;
        });
    }

    /**
     * Returns bool indicating whether Order contains any Products that require physical shipping
     */
    public function hasPhysicalProducts(): bool
    {
        return !!$this->products->some(function ($item) {
            $product = Product::find($item->id);

            return $product->is_physical ?? false;
        });
    }

    /**
     * Checks if there are any wash club membership products on the Order
     */
    public function hasWashClubProducts(): bool
    {
        return $this->products->some(function ($product) {
            return $this->isWashClubProduct($product);
        });
    }

    /**
     * Returns all wash club membership products on the Order
     */
    public function getWashClubProducts(): Collection
    {
        return $this->products->filter(function ($product) {
            return $this->isWashClubProduct($product);
        });
    }

    /**
     * Returns true if the passed product is a Wash Club
     */
    public function isWashClubProduct(Product $product): bool
    {
        $product = Product::find($product->id);

        return $product->is_wash_club ?? false;
    }

    /**
     * Checks if the Order belongs to a given User
     */
    public function belongsToUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Calculate the pre-discount sub total
     */
    public function getPreDiscountSubTotalAttribute(): float
    {
        $preDiscountSubTotalFloat = $this->products->reduce(function ($acc, $product): int {
            return $acc + ($product->pivot->purchase_price_ea * $product->pivot->qty);
        }, 0) / 100;

        return $preDiscountSubTotalFloat;
    }

    /**
     * Converts Discount from dollars to cents
     */
    public function setDiscountAttribute(string $value): void
    {
        $this->attributes['discount'] = self::fromFixedPoint((float)$value);
    }

    /**
     * Converts Discount from cents to dollars
     */
    public function getDiscountAttribute(int $value): float
    {
        return self::toFixedPoint($value);
    }

    /**
     * Converts SubTotal from dollars to cents
     */
    public function setSubTotalAttribute(string $value): void
    {
        $this->attributes['sub_total'] = self::fromFixedPoint((float)$value);
    }

    /**
     * Converts SubTotal from cents to dollars
     */
    public function getSubTotalAttribute(int $value): float
    {
        return self::toFixedPoint($value);
    }

    /**
     * Converts TaxRate from dollars to cents
     */
    public function setTaxRateAttribute(string $value): void
    {
        $this->attributes['tax_rate'] = self::fromFixedPoint((float)$value);
    }

    /**
     * Converts TaxRate from cents to dollars
     */
    public function getTaxRateAttribute(int $value): float
    {
        return self::toFixedPoint($value);
    }

    /**
     * Converts Tax from dollars to cents
     */
    public function setTaxAttribute(string $value): void
    {
        $this->attributes['tax'] = self::fromFixedPoint((float)$value);
    }

    /**
     * Converts Tax from cents to dollars
     */
    public function getTaxAttribute(int $value): float
    {
        return self::toFixedPoint($value);
    }

    /**
     * Converts Total from dollars to cents
     */
    public function setTotalAttribute(string $value): void
    {
        $this->attributes['total'] = self::fromFixedPoint((float)$value);
    }

    /**
     * Converts Total from cents to dollars
     */
    public function getTotalAttribute(int $value): float
    {
        return self::toFixedPoint($value);
    }

    /**
     * Get full shipping name
     */
    public function getShippingFullNameAttribute()
    {
        return $this->shipping_first_name.($this->shipping_first_name && $this->shipping_last_name ? ' ' : '').$this->shipping_last_name;
    }

    /**
     * Get address line 2
     */
    public function getShippingAddressLine2Attribute()
    {
        $city = $this->shipping_city;
        $state = $this->shipping_state;
        $zip = $this->shipping_zip;

        return ($city ?? '').($city && $state ? ', ' : '').($state ?? '').($state && $zip ? ' ' : '').($zip ?? '');
    }

    /**
     * Voyager BREAD Browse accessor for ->merch_status (https://voyager-docs.devdojo.com/customization/bread-accessors)
     */
    public function getMerchStatusBrowseAttribute(): string
    {
        if (!$this->hasPhysicalProducts()) {
            return 'n/a';
        }

        return Order::STATUSES[$this->merch_status] ?? 'n/a';
    }

    /**
     * Voyager BREAD Read accessor for ->merch_status (https://voyager-docs.devdojo.com/customization/bread-accessors)
     */
    public function getMerchStatusReadAttribute(): string
    {
        if (!$this->hasPhysicalProducts()) {
            return 'n/a';
        }

        return Order::STATUSES[$this->merch_status] ?? 'n/a';
    }

    /**
     * Voyager BREAD Browse accessor for ->club_status (https://voyager-docs.devdojo.com/customization/bread-accessors)
     */
    public function getClubStatusBrowseAttribute(): string
    {
        if (!$this->hasWashClubProducts()) {
            return 'n/a';
        }

        return Order::STATUSES[$this->club_status] ?? 'n/a';
    }

    /**
     * Voyager BREAD Read accessor for ->club_status (https://voyager-docs.devdojo.com/customization/bread-accessors)
     */
    public function getClubStatusReadAttribute(): string
    {
        if (!$this->hasWashClubProducts()) {
            return 'n/a';
        }

        return Order::STATUSES[$this->club_status] ?? 'n/a';
    }

    /**
     * Get full name of customer
     */
    public function getCustomerFullNameAttribute()
    {
        if ($this->user) {
            return $this->user->full_name;
        }

        return $this->attributes['customer_first_name'].($this->attributes['customer_first_name'] && $this->attributes['customer_last_name'] ? ' ' : '').$this->attributes['customer_last_name'];
    }

    /**
     * Get email of customer
     */
    public function getCustomerEmailAttribute()
    {
        if ($this->user) {
            return $this->user->email;
        }

        return $this->attributes['user_email'];
    }

    /**
     * Get phone of customer
     */
    public function getCustomerPhoneAttribute()
    {
        if ($this->user) {
            return $this->user->phone;
        }

        return $this->attributes['shipping_phone'];
    }

    /**
     * Get address of customer
     */
    public function getCustomerAddressAttribute()
    {
        if ($this->user) {
            return $this->user->address;
        }

        return $this->attributes['shipping_address'];
    }

    /**
     * Get address line 2 of customer
     */
    public function getCustomerAddressLine2Attribute()
    {
        if ($this->user) {
            return $this->user->address_line_2;
        }

        return '';
    }

    /**
     * Auto-format phone number to XXX.XXX.XXXX
     */
    public function setShippingPhoneAttribute($value): void
    {
        $formattedValue = $value;
        $formattedValue = preg_replace('/[^0-9]/', '', $formattedValue);
        $formattedValue = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{4})/', '$1.$2.$3', $formattedValue);

        $this->attributes['shipping_phone'] = $formattedValue;
    }

    /**
     * Converts price from cents to dollars
     * @return float|int|null
     */
    public function getShippingPriceAttribute($value)
    {
        return $value ? $value / 100 : null;
    }

    /**
     * Converts price from dollars to cents
     *
     * @param float $value|null
     */
    public function setShippingPriceAttribute($value): void
    {
        $this->attributes['shipping_price'] = $value ? $value * 100 : null;
    }

    /**
     * Check status of wash clubs on Gateway and update local order club_status
     */
    public function updateClubStatusFromGateway(): Order
    {
        // Update club_status for washclub orders
        if ($this->club_status === Order::STATUS_PENDING && $this->hasWashClubProducts()) {
            $redeemedAts = [];
            foreach ($this->getWashClubProducts() as $product) {
                $purchase = MembershipPurchase::find($product->pivot->membership_purchase->id ?? null);

                $redeemedAts[] = $purchase->redeemed_at ?? null;
            }

            if (collect($redeemedAts)->every(function ($redeemedAt) {
                return !!$redeemedAt;
            })) {
                $this->setStatus(Order::STATUS_COMPLETED);

                $this->save();
            }
        }

        return $this;
    }

    /**
     * Smart set merch_status and/or club_status
     */
    public function setStatus(string $status): Order
    {
        if ($status === self::STATUS_SHIPPED) {
            if (!$this->hasPhysicalProducts()) {
                return $this;
            }

            $this->merch_status = $status;
        } elseif ($status === self::STATUS_COMPLETED) {
            if (!$this->hasWashClubProducts()) {
                return $this;
            }

            $this->club_status = $status;
        } else {
            if ($this->hasPhysicalProducts()) {
                $this->merch_status = $status;
            }

            if ($this->hasWashClubProducts()) {
                $this->club_status = $status;
            }
        }

        return $this;
    }
}
