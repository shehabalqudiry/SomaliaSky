<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
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
            "UserID"                => $this->user->id ?? 0,
            "User"                  => $this->user,
            "Store"                 => $this->store,
            "Type"                  => $this->type,
            "Title"                 => $this->getTranslation('title', app()->getLocale()) ?? '',
            "Category"              => $this->getCategory(),
            "City"                  => $this->city ? $this->city->country->name . " - ". $this->city->name : 'تم حذف المدينة',
            "Currency"              => $this->currency ? $this->currency->name : 'غير محدد',
            "IsFeatured"            => $this->is_featured ? true : false,
            "Description"           => $this->getTranslation('description', app()->getLocale()) ?? '',
            "AnnouncementNumber"    => $this->number ?? '',
            "Price"                 => $this->price ?? 0,
            "ShareLink"             => route('front.announcements.show', $this),
            "Rate"                  => $this->rate ?? 0,
            "Images"                => $this->imagesArray() ?? [],
            "Attributes"            => $this->AnnouncementAttribute ? AnnouncementAttributeResource::collection($this->AnnouncementAttribute) : '',
        ];
        return $data;
    }
}
