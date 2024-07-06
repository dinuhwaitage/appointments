<?php

namespace App\Http\Resources\Patients;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientDetailResource extends JsonResource
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
                'id' => optional($this->contact)->id,
                'first_name' => optional($this->contact)->first_name,
                'last_name' => optional($this->contact)->last_name,
                'email' => optional($this->contact)->email,
                'mobile' => optional($this->contact)->mobile,
                'status' => optional($this->contact)->status,
                'created_at' => optional($this->contact)->created_at,
                'updated_at' => optional($this->contact)->updated_at
            ],
            'address' =>  [
                'id' => optional($this->address)->id,
                'line1' => optional($this->address)->line1,
                'line2' => optional($this->address)->line2,
                'city' => optional($this->address)->city,
                'state' => optional($this->address)->state,
                'zipcode' => optional($this->address)->zipcode,
                'status' => optional($this->address)->status,
                'created_at' => optional($this->address)->created_at,
                'updated_at' => optional($this->address)->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
