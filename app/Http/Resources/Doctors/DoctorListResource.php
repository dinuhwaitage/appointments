<?php

namespace App\Http\Resources\Doctors;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorListResource extends JsonResource
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
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'date_of_join' => $this->date_of_join,
            'designation' => $this->designation,
            'qualification' => $this->qualification,
            'specification'=> $this->specification,
            'contact' =>  $this->contact,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
