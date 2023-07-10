<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            "Package"               => $this->title,
            "PackageDescription"    => $this->description,
            "Cost"                  => $this->price,
            "AnnouncementNumber"    => $this->announcement_number,
            "Time"                  => $this->time,
        ];
        return $data;



    }
}
