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
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'registration_date' => $this->registration_date,
            'package_start_date' => $this->package_start_date,
            'assets' => $this->assets,
            'number' => $this->number,
            'package_end_date' => $this->package_end_date,
            'abha_number' => $this->abha_number,
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'seating_count' => optional($this->package)->seating_count,
                'available_count' => $this->available_count,
                'available_count_old' => (optional($this->package)->id)?$this->available_package_count($this->package->id) : null,
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
