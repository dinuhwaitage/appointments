<?php

namespace App\Http\Resources\Appointments;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentSlimResource extends JsonResource
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
            'fee' => $this->fee,
            'status' => $this->status,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional(optional($this->patient)->contact)->name,
            ],
            'doctor' => [ 
                'id' =>  optional($this->doctor)->id,
                'name' => optional(optional($this->doctor)->contact)->getFullName(),
            ]
        ];
    }
}
