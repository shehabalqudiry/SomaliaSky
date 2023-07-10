<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "ID"                    => $this->id,
            "Name"                  => $this->title,
            "Image"                 => $this->image(),
            "CategoryAttributes"    => CategoryAttributeResource::collection($this->attributes)
        ];
        return $data;
    }
}
