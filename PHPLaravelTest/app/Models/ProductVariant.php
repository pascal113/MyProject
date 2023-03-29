<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Models\Product;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model implements Buyable
{
    use \Gloudemans\Shoppingcart\CanBeBought;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'product_id',
        'wash_connect_id',
        'wash_connect_program_id',
        'name',
        'price',
        'is_recurring',
    ];

    /**
     * Get product category
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Is For Hire?
     */
    public function getIsForHireAttribute(): bool
    {
        return !!preg_match('/-for-hire$/', $this->slug);
    }

    /**
     * Is BIP?
     */
    public function getIsBipAttribute(): bool
    {
        return !!preg_match('/-bip-/', $this->slug);
    }

    /**
     * Is Promo?
     */
    public function getIsPromoAttribute(): bool
    {
        return !!preg_match('/-promo$/', $this->slug);
    }

    /**
     * Is Limited?
     */
    public function getIsLimitedAttribute(): bool
    {
        return !!preg_match('/-limited$/', $this->slug);
    }

    /**
     * Is Beary Clean level?
     */
    public function getIsCleanAttribute(): bool
    {
        return !!preg_match('/^beary-clean/', $this->slug);
    }

    /**
     * Is Beary Bright level?
     */
    public function getIsBrightAttribute(): bool
    {
        return !!preg_match('/^beary-bright/', $this->slug);
    }

    /**
     * Is Beary Best level?
     */
    public function getIsBestAttribute(): bool
    {
        return !!preg_match('/^beary-best/', $this->slug);
    }

    /**
     * Is Monthly?
     */
    public function getIsMonthlyAttribute(): bool
    {
        return !!(preg_match('/-monthly$/', $this->slug) || preg_match('/-for-hire$/', $this->slug));
    }

    /**
     * Is Yearly?
     */
    public function getIsYearlyAttribute(): bool
    {
        return !!preg_match('/-yearly$/', $this->slug);
    }

    /**
     * Is OneYear?
     */
    public function getIsOneYearAttribute(): bool
    {
        return !!preg_match('/-one-year$/', $this->slug);
    }

    /**
     * Return a friendly name for the list view
     */
    public function getNameBrowseAttribute(): string
    {
        return $this->product->name.' ⟶ '.$this->name;
    }

    /**
     * Converts price from dollars to cents
     *
     * @param float $value|null
     */
    public function setPriceAttribute($value): void
    {
        $this->attributes['price'] = $value ? $value * 100 : null;
    }

    /**
     * Converts price from cents to dollars
     * @return float|int|null
     */
    public function getPriceAttribute($value)
    {
        return $value ? $value / 100 : null;
    }
}
