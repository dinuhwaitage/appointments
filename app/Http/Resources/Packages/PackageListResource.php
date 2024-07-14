<?php

namespace App\Http\Resources\Packages;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageListResource extends JsonResource
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
            'amount' => $this->amount,
            'description' => $this->description,
            'status' => $this->status,
            'clinic' =>[
                'id' => $this->clinic->id,
                'name' => $this->clinic->name
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
