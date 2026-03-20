<?php

namespace App\Http\Resources\Subscriptions;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'plan_price' => optional($this->plan)->price,
            'description' => $this->description,
            'status' => $this->status,
            'plan' =>[
                'id' => optional($this->plan)->id,
                'name' => optional($this->plan)->name
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
