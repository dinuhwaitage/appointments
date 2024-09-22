<?php

namespace App\Http\Resources\Clinics;

use Illuminate\Http\Resources\Json\JsonResource;

class ClinicDetailResource extends JsonResource
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
            'name' => $this->name,
            'number' => $this->number,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'description' => $this->description,
            'logo_url' => $this->logo_url,
            'registration_date' => $this->registration_date,
            'gst_number' => $this->gst_number,
            'logo' =>   [
                'id' =>  optional($this->logo)->id,
                'mime_type' =>  optional($this->logo)->mime_type,
                'file_name' =>  optional($this->logo)->file_name,
                'file_size' =>  optional($this->logo)->file_size,
                'url' =>  optional($this->logo)->url
            ],
            'favicon' =>   [
                'id' =>  optional($this->favicon)->id,
                'mime_type' =>  optional($this->favicon)->mime_type,
                'file_name' =>  optional($this->favicon)->file_name,
                'file_size' =>  optional($this->favicon)->file_size,
                'url' =>  optional($this->favicon)->url
            ],
            'scanner' =>   [
                'id' =>  optional($this->scanner)->id,
                'mime_type' =>  optional($this->scanner)->mime_type,
                'file_name' =>  optional($this->scanner)->file_name,
                'file_size' =>  optional($this->scanner)->file_size,
                'url' =>  optional($this->scanner)->url
            ],
            'assets' => $this->assets,
            'address' =>  [
                'id' => optional($this->address)->id,
                'line1' => optional($this->address)->line1,
                'line2' => optional($this->address)->line2,
                'city' => optional($this->address)->city,
                'state' => optional($this->address)->state,
                'zipcode' => optional($this->address)->zipcode
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
