<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "ID"            => $this->id,
            "Name"          => $this->name,
            "PhoneNumber"   => $this->phone,
            "City"          => $this->city->name ?? "غير متاح",
            "EmailAddress"  => $this->email,
            "Avatar"        => $this->getUserAvatar(),
            "API_TOKEN"     => $this->blocked == 1 ? "User Blocked" : $this->api_key
        ];
        if ($this->subscription) {
            $data["Subscriptions"] = SubscriptionResource::collection($this->subscription);
        }

        $data['Rating'] = $this->rate_my->avg('stars') ?? 0;

        return $data;
    }
}
