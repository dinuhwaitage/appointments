<?php

namespace App\Http\Resources\Patients;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientSlimResource extends JsonResource
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
            'name' => $this->contact->name,
            'is_expiring_soon' => $this->is_expiring_soon(),
            'package' =>[
                'id' => optional($this->package)->id,
                'name' => optional($this->package)->name,
                'seating_count' => optional($this->package)->seating_count,
                'available_count' => $this->available_count,
                'available_count_old' => (optional($this->package)->id)?$this->available_package_count($this->package->id) : null,
                'amount' => optional($this->package)->amount
            ],
        ];
    }
}
