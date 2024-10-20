<?php

namespace App\Http\Resources\Invoices;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePrintResource extends JsonResource
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
            'rnd_number' => $this->rnd_number,
            'status' => $this->status,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional($this->patient->contact)->name,
                'mobile' => optional($this->patient->contact)->mobile
            ],
            'clinic' =>[ 
                'id' =>  $this->clinic->id,
                'name' => $this->clinic->name,
                'city' =>  optional(optional($this->clinic)->address)->city,
                'logo_url' => $this->clinic->logo_url,
                'logo' =>   [
                    'id' =>  optional(optional($this->clinic)->logo)->id,
                    'mime_type' =>  optional(optional($this->clinic)->logo)->mime_type,
                    'file_name' =>  optional(optional($this->clinic)->logo)->file_name,
                    'file_size' =>  optional(optional($this->clinic)->logo)->file_size,
                    'url' =>  optional(optional($this->clinic)->logo)->url
                ]
                ]
        ];
    }
}
