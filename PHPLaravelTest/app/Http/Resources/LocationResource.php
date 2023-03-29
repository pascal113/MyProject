<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->sanitizeForJs();

        return [
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'id' => $this->id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'manager_name' => $this->manager_name,
            'manager_title' => $this->manager_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_title' => $this->meta_title,
            'meta_same_as' => $this->meta_same_as,
            'miles_distance' => $this->miles_distance ?? null,
            'phone' => $this->phone,
            'temporarily_closed' => $this->temporarily_closed,
            'services' => $this->services,
            'site_number' => $this->site_number,
            'wash_connect_id' => $this->wash_connect_id,
            'title' => $this->title,
            'updated_at' => $this->updated_at,
            'url' => $this->url,
        ];
    }
}
