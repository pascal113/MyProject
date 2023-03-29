<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Coupon extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'percent_discount',
        'minimum_cart_total',
        'expires_after_num_uses',
    ];

    /**
     * Auto-uppercase all codes
     */
    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Define products relationship
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    /**
     * Return collection of Products and ProductVariants
     * that this coupon can be applied to.
     */
    public function getApplicableProductsAndProductVariants(): Collection
    {
        $relations = $this->products()->withPivot([ 'product_variant_id' ])->get();

        $productsAndProductVariants = $relations->map(function ($relation) {
            if ($relation->pivot->product_variant_id ?? null) {
                return ProductVariant::find($relation->pivot->product_variant_id);
            }

            return $relation;
        });

        return $productsAndProductVariants;
    }

    /**
     * Return bool whether this coupon can be applied to the passed CartItem
     */
    public function isApplicableTo(CartItem $productOrProductVariant): bool
    {
        $applicableProductsAndVariants = $this->getApplicableProductsAndProductVariants();

        return !!$applicableProductsAndVariants->first(function ($applicableProductOrProductVariant) use ($productOrProductVariant) {
            if ($applicableProductOrProductVariant instanceof ProductVariant) {
                return $productOrProductVariant->id === $applicableProductOrProductVariant->product->id && ((int)$productOrProductVariant->options['variant_id'] ?? null) === $applicableProductOrProductVariant->id;
            } elseif ($applicableProductOrProductVariant instanceof Product) {
                return $productOrProductVariant->id === $applicableProductOrProductVariant->id;
            }

            return false;
        });
    }

    /**
     * Find a coupon by `code`
     */
    public static function getByCode(string $code): Collection
    {
        return self::where('code', $code)->get();
    }

    /**
     * Return bool whether the coupon is expired
     * due to date and/or number of uses limits.
     *
     * @return boolean
     */
    public function getIsExpiredAttribute(): bool
    {
        return Carbon::now() >= Carbon::parse($this->expires_at) || $this->num_uses >= $this->expires_after_num_uses;
    }

    /**
     * Return collection of all products and product variants
     * that a coupon can be assigned to or used for.
     */
    public static function getAllAvailableProductsAndProductVariants(): Collection
    {
        $productsAndProductVariants = Product::all()->reduce(function ($acc, $product) {
            if ($product->variants->count()) {
                foreach ($product->variants as $variant) {
                    $variant->fullName = Product::getNameWithTerm($product, $variant);

                    array_push($acc, $variant);
                }
            } else {
                $product->fullName = $product->name;

                array_push($acc, $product);
            }

            return $acc;
        }, []);

        usort($productsAndProductVariants, function ($a, $b) {
            return strcmp($a->fullName, $b->fullName);
        });

        return collect($productsAndProductVariants);
    }

    /**
     * Return a collection of product and product variant
     * data for display in a select dropdown.
     */
    public static function getProductAndProductVariantOptions(): Collection
    {
        $productsAndProductVariants = self::getAllAvailableProductsAndProductVariants();

        $results = $productsAndProductVariants
            ->reduce(function ($acc, $productOrProductVariant) {
                if ($productOrProductVariant instanceof ProductVariant) {
                    $id = 'product.'.$productOrProductVariant->product->id.'.variant.'.$productOrProductVariant->id;
                } elseif ($productOrProductVariant instanceof Product) {
                    $id = 'product.'.$productOrProductVariant->id;
                }

                if (!isset($id)) {
                    return $acc;
                }

                return array_merge($acc, [ (object)[
                    'id' => $id,
                    'text' => $productOrProductVariant->fullName,
                ] ]);
            }, []);

        return collect($results);
    }
}
