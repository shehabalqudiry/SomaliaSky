<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            "ID"                            => $this->id,
            "Cost"                          => $this->price,
            "Paid"                          => $this->price == 0 ? 'باقة مجانية' : ($this->paid == 0 ? 'لم يتم الدفع' : 'تم الدفع'),
            "Status"                        => $this->status == 0 ? 'غير مفعل' : 'مفعل',
            "StartDate"                     => $this->start_date,
            "EndDate"                       => $this->end_date,
        ];
        if ($this->package) {
            $AnnouncementCurrent =($this->package->announcement_number - $this->user->announcements->count());
            $data["Package"]                       = $this->package->title;
            $data["AnnouncementInPackage"]         = $this->package->announcement_number;
            $data["AnnouncementCurrent"]           = ($AnnouncementCurrent - $this->package->announcement_number) < 1 ? 0 : $AnnouncementCurrent;
        }
        return $data;



    }
}
