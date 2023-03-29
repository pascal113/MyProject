<?php

namespace App\Models;

use App\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Products within this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category', 'id');
    }
}
