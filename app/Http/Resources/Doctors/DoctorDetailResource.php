<?php

namespace App\Http\Resources\Doctors;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorDetailResource extends JsonResource
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
            'code' => $this->code,
            'date_of_birth' => $this->date_of_birth,
            'date_of_join' => $this->date_of_join,
            'designation' => $this->designation,
            'qualification' => $this->qualification,
            'contact' => $this->contact,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
