<?php

namespace App\Http\Resources\Invoices;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDetailResource extends JsonResource
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
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'paid_by' => $this->paid_by,
            'description' => $this->description,
            'transaction_number' => $this->transaction_number,
            'status' => $this->status,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional($this->patient->contact)->name
            ],
            'clinic' =>[
                'id' => $this->clinic->id,
                'name' => $this->clinic->name
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
