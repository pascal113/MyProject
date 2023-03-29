<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductVariantCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (ProductVariant $variant) {
            return new ProductVariantResource($variant);
        });

        return parent::toArray($request);
    }
}
