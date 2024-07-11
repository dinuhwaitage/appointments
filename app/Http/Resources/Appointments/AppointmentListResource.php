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
            'patient' =>[ 
                'id' =>  $this->patient->id,
                'name' => optional($this->patient->contact)->first_name." ".optional($this->patient->contact)->last_name,
            ],
            'doctor' => [ 
                'id' =>  $this->doctor->id,
                'name' => optional($this->doctor->contact)->first_name." ".optional($this->doctor->contact)->last_name,
            ],
            'clinic' => $this->clinic,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
