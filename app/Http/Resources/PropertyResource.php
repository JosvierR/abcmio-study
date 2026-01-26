<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            "id"=>$this->id,
            "is_public"=>$this->is_public,
            "created_date"=>$this->created_at,
            "start_date"=>$this->start_date,
            "expire_date"=>$this->expire_date,
        ];
    }
}
