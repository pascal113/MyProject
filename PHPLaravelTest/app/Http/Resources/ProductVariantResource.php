<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slug' => $this->slug,
            'product_id' => $this->product_id,
            'wash_connect_id' => $this->wash_connect_id,
            'wash_connect_program_id' => $this->wash_connect_program_id,
            'product_name' => $this->product->name,
            'name' => $this->name,
            'price' => $this->price,
            'is_monthly' => $this->is_monthly,
            'is_yearly' => $this->is_yearly,
            'is_one_year' => $this->is_one_year,
            'is_recurring' => $this->is_recurring,
            'is_promo' => $this->is_promo,
        ];
    }
}
