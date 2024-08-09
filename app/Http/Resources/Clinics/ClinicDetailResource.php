<?php

namespace App\Http\Resources\Clinics;

use Illuminate\Http\Resources\Json\JsonResource;

class ClinicDetailResource extends JsonResource
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
            'name' => $this->name,
            'number' => $this->number,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'description' => $this->description,
            'address' =>  [
                'id' => $this->address->id,
                'line1' => $this->address->line1,
                'line2' => $this->address->line2,
                'city' => $this->address->city,
                'state' => $this->address->state,
                'zipcode' => $this->address->zipcode
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
