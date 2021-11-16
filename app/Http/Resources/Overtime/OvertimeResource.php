<?php

namespace App\Http\Resources\Overtime;

use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
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
            'user_id'       =>  $this->user_id,
            'date'          =>  $this->date,
            'from'          =>  [
                'standard'  =>  date("g:i A", strtotime($this->from)),
                'other'     =>  $this->from
            ],
            'to'            =>  [
                'standard'  =>  date("g:i A", strtotime($this->to)),
                'other'     =>  $this->to
            ],
            'reason'        =>  $this->reason,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }
}
