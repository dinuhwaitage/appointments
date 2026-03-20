<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentListResource extends JsonResource
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
            'user_id' => $this->user_id,
            'clinic_id' => $this->clinic_id,
            'subscription_id' => $this->subscription_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id,
            'payment_date' => $this->payment_date,
            'description' => $this->description,
            'discount_amount' => $this->discount_amount,
            'subscription' => [
                'id' => optional($this->subscription)->id,
                'plan_id' => optional($this->subscription)->plan_id,
                'start_date' => optional($this->subscription)->start_date,
                'end_date' => optional($this->subscription)->end_date,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
