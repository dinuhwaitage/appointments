<?php

namespace App\Http\Resources\Appointments;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentListResource extends JsonResource
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
            'doctor_note'=> $this->doctor_note,
            'fee' => $this->fee,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional(optional($this->patient)->contact)->name,
            ],
            'doctor' => [ 
                'id' =>  optional($this->doctor)->id,
                'name' => optional(optional($this->doctor)->contact)->getFullName(),
            ],
            'clinic' =>[
                'id' => $this->clinic->id,
                'name' => $this->clinic->name
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
