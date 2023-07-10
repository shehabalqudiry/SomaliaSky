<?php

namespace App\Http\Resources;

use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            "Name"                  => $this->name ?? '',
			"Category"              => $this->category->title ?? 'تم حذف القسم',
			"City"                  => $this->city->name ?? 'تم حذف المدينة',
			"Verify"                => $this->is_featured ? true : false,
			"Location"              => $this->location ?? '',
			"Avatar"                => $this->avatar_image(),
			"Cover"                 => $this->cover(),
			"Description"           => $this->description ?? '',
			"Announcements"         => AnnouncementResource::collection($this->user->announcements),
			"Blocked"               => $this->blocked ? true : false,
        ];
        return $data;
    }
}
