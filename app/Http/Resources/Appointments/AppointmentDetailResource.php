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
            'weight' => $this->weight,
            'height' => $this->height,
            'seating_no' => $this->seating_no,
            'bp_detail' => $this->bp_detail,
            'medical_history' => $this->medical_history,
            'family_medical_history' => $this->family_medical_history,
            'current_condition' => $this->current_condition,
            'observation_details' => $this->observation_details,
            'investigation_details' => $this->investigation,
            'treatment_plan' => $this->treatment_plan,
            'procedures' => $this->procedures,
            'patient' =>[ 
                'id' =>  optional($this->patient)->id,
                'name' => optional($this->patient->contact)->name,
                'date_of_birth' =>  optional($this->patient)->date_of_birth,
                'gender' =>  optional($this->patient)->gender,
                'assets' => optional($this->patient)->assets
            ],
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'seating_count' => optional($this->package)->seating_count,
                'available_count' => (optional($this->package)->id)?$this->patient->available_package_count($this->package->id) : null,
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
