<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductShippingPrice extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'qty_from',
        'qty_to',
        'price_each',
    ];

    /**
     * Product related to shipping cost
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Converts price from cents to dollars
     * @return float|int|null
     */
    public function getPriceEachAttribute($value)
    {
        return $value ? $value / 100 : 0;
    }

    /**
     * Converts price from dollars to cents
     *
     * @param float $value|null
     */
    public function setPriceEachAttribute($value): void
    {
        $this->attributes['price_each'] = $value ? $value * 100 : 0;
    }
}
