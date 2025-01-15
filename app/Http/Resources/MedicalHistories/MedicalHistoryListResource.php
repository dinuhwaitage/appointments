<?php

namespace App\Http\Resources\MedicalHistories;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryListResource extends JsonResource
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
            'patient_detail' => $this->patient_detail,
            'patient_start_date' => $this->patient_start_date,
            'family_detail' => $this->family_detail,
            'family_start_date' => $this->family_start_date,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional(optional($this->patient)->contact)->name,
            ]
        ];
    }
}
