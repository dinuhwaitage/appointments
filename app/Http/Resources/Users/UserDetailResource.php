<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
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
            'contact' =>   [
                'id' => $this->contact->id,
                'first_name' => $this->contact->first_name,
                'last_name' => $this->contact->last_name,
                'email' => $this->contact->email,
                'mobile' => $this->contact->mobile,
                'status' => $this->contact->status
                //'patient' => $this->contact->patient,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
