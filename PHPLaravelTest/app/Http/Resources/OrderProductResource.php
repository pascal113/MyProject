<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->product;
        $product->name_with_term = Product::getNameWithTerm($product, $this->variant);

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product' => $product,
            'product_variant_id' => $this->product_variant_id,
            'variant' => new ProductVariantResource($this->variant),
            'coupon_code' => $this->coupon_code,
            'purchase_price_ea' => $this->purchase_price_ea,
            'num_washes_ea' => $this->num_washes_ea,
            'qty' => $this->qty,
            'pre_discount_sub_total' => $this->pre_discount_sub_total,
            'discount' => $this->discount,
            'sub_total' => $this->sub_total,
            'tax' => $this->tax,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
