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
            'status' => $this->status,
            'patient' =>[ 
                'name' => optional($this->patient->contact)->name,
                'mobile' => optional($this->patient->contact)->mobile,
                'address' =>  [
                    'line1' => optional($this->patient->address)->line1,
                    'line2' => optional($this->patient->address)->line2,
                    'city' => optional($this->patient->address)->city,
                    'state' => optional($this->patient->address)->state,
                    'zipcode' => optional($this->patient->address)->zipcode
                ]
            ],
            'clinic' =>[ 
                'email' => $this->clinic->email,
                'phone' => $this->clinic->phone,
                'website' => $this->clinic->website,
                'number' => $this->clinic->number,
                'name' => $this->clinic->name,
                'logo_url' =>  optional(optional($this->clinic)->logo)->url,
                'scanner_url' =>  optional(optional($this->clinic)->scanner)->url,
                'address' =>  [
                    'line1' => optional($this->address)->line1,
                    'line2' => optional($this->address)->line2,
                    'city' => optional($this->address)->city,
                    'state' => optional($this->address)->state,
                    'zipcode' => optional($this->address)->zipcode
                    ],
                ]
        ];
    }
}
