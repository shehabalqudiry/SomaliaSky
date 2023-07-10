<?php

namespace App\Http\Resources;

use App\Models\CategoryAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementAttributeResource extends JsonResource
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
            "Name"                  => $this->name ? CategoryAttribute::find($this->name)->name : '',
            "Value"                 => $this->value ?? '',
        ];
        return $data;
    }
}
