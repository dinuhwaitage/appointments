<?php

namespace App\Http\Resources\Appointments;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDetailResource extends JsonResource
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
            'date' => $this->date,
            'time' => $this->time,
            'details' => $this->details,
            'status' => $this->status,
            'diagnosis' => $this->diagnosis,
            'fee' => $this->fee,
            'doctor_note'=> $this->doctor_note,
            'assets' => $this->assets,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional($this->patient->contact)->name,
                'date_of_birth' =>  optional($this->patient)->date_of_birth,
                'gender' =>  optional($this->patient)->gender
            ],
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'seating_count' => optional($this->package)->seating_count,
                'available_count' => $this->patient->available_count,
                'available_count_old' => (optional($this->package)->id)?$this->patient->available_package_count($this->package->id) : null,
                'amount' => optional($this->package)->amount
            ],
            'doctor' => [ 
                'id' =>  optional($this->doctor)->id,
                'name' => optional($this->doctor->contact)->getFullName(),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
