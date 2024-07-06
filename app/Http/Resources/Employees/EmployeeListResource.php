<?php

namespace App\Http\Resources\Employees;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
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
                'id' => optional($this->contact)->id,
                'first_name' => optional($this->contact)->first_name,
                'last_name' => optional($this->contact)->last_name,
                'email' => optional($this->contact)->email,
                'mobile' => optional($this->contact)->mobile,
                'status' => optional($this->contact)->status,
                'created_at' => optional($this->contact)->created_at,
                'updated_at' => optional($this->contact)->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
