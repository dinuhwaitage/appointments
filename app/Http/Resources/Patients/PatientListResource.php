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
            'gender' => $this->gender,
            'description' => $this->description,
            'date_of_birth' => $this->date_of_birth,
            'registration_date' => $this->registration_date,
            'number' => $this->number,
            'package_end_date' => $this->package_end_date,
            'abha_number' => $this->abha_number,
            'is_expiring_soon' => $this->is_expiring_soon(),
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'amount' => optional($this->package)->amount
            ],
            'contact' =>   [
                'id' => optional($this->contact)->id,
                'name' => optional($this->contact)->name,
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
