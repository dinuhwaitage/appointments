<?php

namespace App\Http\Resources\Appointments;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentHistoryResource extends JsonResource
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
            'patient_name' => optional(optional($this->patient)->contact)->name,
            'doctor_name' => optional(optional($this->doctor)->contact)->getFullName()
        ];
    }
}
