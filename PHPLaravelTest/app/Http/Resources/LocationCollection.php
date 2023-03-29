<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Location $location) {
            return new LocationResource($location);
        });

        return parent::toArray($request);
    }
}
