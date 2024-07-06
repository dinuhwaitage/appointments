<?php

namespace App\Http\Resources\Patients;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientListResource extends JsonResource
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
            'description' => $this->description,
            'contact' =>   [
                'id' => $this->contact->id,
                'first_name' => $this->contact->first_name,
                'last_name' => $this->contact->last_name,
                'email' => $this->contact->email,
                'mobile' => $this->contact->mobile,
                'status' => $this->contact->status,
                'created_at' => $this->contact->created_at,
                'updated_at' => $this->contact->updated_at
            ],
            'address' =>  [
                'id' => $this->address->id,
                'line1' => $this->address->line1,
                'line2' => $this->address->line2,
                'city' => $this->address->city,
                'state' => $this->address->state,
                'zipcode' => $this->address->zipcode,
                'status' => $this->address->status,
                'created_at' => $this->address->created_at,
                'updated_at' => $this->address->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
