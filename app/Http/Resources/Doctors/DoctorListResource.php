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
            'date_of_birth' => $this->date_of_birth,
            'date_of_join' => $this->date_of_join,
            'designation' => $this->designation,
            'qualification' => $this->qualification,
            'contact' =>  [
                'id' => $this->contact->id,
                'first_name' => $this->contact->first_name,
                'last_name' => $this->contact->last_name,
                'email' => $this->contact->email,
                'mobile' => $this->contact->mobile,
                'status' => $this->contact->status,
                'created_at' => $this->contact->created_at,
                'updated_at' => $this->contact->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
