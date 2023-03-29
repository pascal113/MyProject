<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\ProductShippingPrice;
use App\Models\ProductVariant;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model implements Buyable
{
    use \Gloudemans\Shoppingcart\CanBeBought;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'image',
        'num_washes',
    ];

    /**
     * Get product category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    /**
     * Get variants
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    /**
     * Get prices
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    /**
     * Get shipping prices
     */
    public function shipping_prices(): HasMany
    {
        return $this->hasMany(ProductShippingPrice::class, 'product_id', 'id');
    }

    /**
     * Return product name, prefixed & suffixed in the case of a Wash club
     */
    public function getNameAttribute(): string
    {
        if ($this->is_wash_club) {
            return self::appendName($this->attributes['name']);
        }

        return $this->attributes['name'] ?? '';
    }

    /**
     * Prevent product name in BREAD from including the appended info
     */
    public function getNameEditAttribute(): string
    {
        return $this->getRawOriginal('name') ?? '';
    }

    /**
     * Apply prefix & suffix to string name
     */
    public static function appendName(?string $name = null): ?string
    {
        if (!$name) {
            return null;
        }

        return 'Beary '.$name.' Unlimited Wash Club Membership';
    }

    /**
     * Return full product name. Includes Term and is prefixed & suffixed in the case of a Wash club
     */
    public static function getNameWithTerm(Product $product, ?ProductVariant $variant = null): string
    {
        if ($product->is_wash_club) {
            if ($variant) {
                return 'Beary '.$product->attributes['name'].' '.($variant->name ?? '').' Unlimited Wash Club';
            }

            return 'Beary '.$product->attributes['name'].' Unlimited Wash Club';
        }

        return $product->attributes['name'];
    }

    /**
     * Return name ("description") for Buyable implementation (for Shopping Cart)
     */
    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    /**
     * Return price for Product or ProductVariant * Qty
     */
    public function getPrice(?\App\Models\ProductVariant $variant = null, int $qty): ?float
    {
        return $this->getPriceEach($variant, $qty) * $qty;
    }

    /**
     * Return price for Product or ProductVariant
     */
    public function getPriceEach(?\App\Models\ProductVariant $variant = null, int $qty): ?float
    {
        if ($variant) {
            $price = $variant->price;
        } else {
            $price = $this->getPriceEachByQuantity($qty);
        }

        return $price;
    }

    /**
     * Return the price each for this product, given a quantity
     */
    private function getPriceEachByQuantity(int $qty): float
    {
        $price = $this->prices()->orderBy('qty_from', 'desc')->get()->first(function ($price) use ($qty) {
            return $price->qty_from <= $qty && (!$price->qty_to || $price->qty_to >= $qty);
        });

        return $price->price_each ?? 0;
    }

    /**
     * Derive full URL from id and name
     */
    public function getUrlAttribute()
    {
        return route('products.show', [
            $this->id,
            Str::slug($this->name),
        ]);
    }

    /**
     * Return true if product is a "digital delivery" product
     *
     * Digital Delivery products are products that are delivered via email/web access,
     *   and therefore require a user account to purchase.
     */
    public function getIsDigitalAttribute(): bool
    {
        return $this->category->slug === 'memberships';
    }

    /**
     * Return true if product is a Wash Club product
     */
    public function getIsWashClubAttribute(): bool
    {
        return ($this->category->slug ?? null) === 'memberships';
    }

    /**
     * Return true if product is a "physical" product
     *
     * Physical products are products that are delivered via mail (UPS, etc),
     *   and therefore require a shipping address to purchase.
     */
    public function getIsPhysicalAttribute(): bool
    {
        return in_array($this->category->slug, [ 'wash-cards-ticket-books', 'branded-merchandise' ]);
    }

    /**
     * Return the shipping price each for this product, given a quantity
     */
    public function getShippingPriceEachByQuantity(int $qty): float
    {
        $shippingPrice = $this->shipping_prices()->orderBy('qty_from', 'desc')->get()->first(function ($shippingPrice) use ($qty) {
            return $shippingPrice->qty_from <= $qty && (!$shippingPrice->qty_to || $shippingPrice->qty_to >= $qty);
        });

        return $shippingPrice->price_each ?? 0;
    }

    /**
     * Return the shipping price for this product, given a quantity
     */
    public function getShippingPriceByQuantity(int $qty): float
    {
        return $this->getShippingPriceEachByQuantity($qty) * $qty;
    }
}
