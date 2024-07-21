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
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional($this->patient->contact)->getFullName(),
                'date_of_birth' =>  optional($this->patient)->date_of_birth,
                'gender' =>  optional($this->patient)->gender
            ],
            'doctor' => [ 
                'id' =>  optional($this->doctor)->id,
                'name' => optional($this->doctor->contact)->getFullName(),
            ],
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'amount' => optional($this->package)->amount
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
