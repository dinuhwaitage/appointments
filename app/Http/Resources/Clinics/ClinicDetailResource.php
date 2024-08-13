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
                'id' => optional($this->address)->id,
                'line1' => optional($this->address)->line1,
                'line2' => optional($this->address)->line2,
                'city' => optional($this->address)->city,
                'state' => optional($this->address)->state,
                'zipcode' => optional($this->address)->zipcode
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
