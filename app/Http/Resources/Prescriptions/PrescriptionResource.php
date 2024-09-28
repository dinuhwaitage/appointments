<?php

namespace App\Http\Resources\Prescriptions;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'medicine' => $this->medicine,
            'dosages' => $this->dosages,
            'duration' => $this->duration,
            'qty' => $this->qty,
            'notes' => $this->notes,
            'appointment' => $this->appointment
        ];
    }
}
