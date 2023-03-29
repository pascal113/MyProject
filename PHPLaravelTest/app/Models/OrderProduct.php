<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\MembershipModification;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderProduct extends \Illuminate\Database\Eloquent\Relations\Pivot
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
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
        'status_message',
        'created_at',
        'updated_at',
    ];

    /**
     * Define order relationship
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Define product relationship
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Define variant relationship
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    /**
     * ->membership_purchase
     */
    public function membership_purchase(): HasOne
    {
        return $this->hasOne(MembershipPurchase::class, 'source_order_line_item_id', 'id');
    }

    /**
     * ->modification
     */
    public function modification(): HasOne
    {
        return $this->hasOne(MembershipModification::class, 'source_order_line_item_id', 'id');
    }

    /**
     * ->display_purchase_price
     */
    public function getDisplayPurchasePriceAttribute(): ?string
    {
        if (!$this->purchase_price) {
            return null;
        }

        return number_format($this->purchase_price, 2).($this->variant->is_monthly ? '/mo' : '').($this->variant->is_yearly ? '/yr' : '');
    }

    /**
     * ->purchase_price
     */
    public function getPurchasePriceAttribute(): ?float
    {
        return $this->variant->price ?? null;
    }

    /**
     * ->membership_details_url
     */
    public function getMembershipDetailsUrlAttribute(): ?string
    {
        $id = $this->modification->target_membership_wash_connect_id ?? $this->reactivates_wash_connect_id ?? $this->membership_purchase->id ?? null;

        if (!$id) {
            return null;
        }

        return route('my-account.memberships.show', [ 'id' => $id ]);
    }
}
