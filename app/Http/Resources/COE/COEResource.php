<?php

namespace App\Http\Resources\COE;

use Illuminate\Http\Resources\Json\JsonResource;

class COEResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->id,
            'reason'        =>  $this->reason,
            'date_needed'   =>  $this->date_needed,
            'compensation'  =>  $this->compensation,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }
}
