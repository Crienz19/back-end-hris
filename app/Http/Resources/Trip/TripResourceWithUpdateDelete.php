<?php

namespace App\Http\Resources\Trip;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResourceWithUpdateDelete extends JsonResource
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
            'id'                =>  $this->id,
            'date_from'         =>  $this->date_from,
            'date_to'           =>  $this->date_to,
            'time_in'           =>  [
                'standard'  =>  date("g:i A", strtotime($this->time_in)),
                'other'     =>  $this->time_in
            ],
            'time_out'          =>  [
                'standard'  =>  date("g:i A", strtotime($this->time_out)),
                'other'     =>  $this->time_out
            ],
            'destination_from'  =>  $this->destination_from,
            'destination_to'    =>  $this->destination_to,
            'purpose'           =>  $this->purpose,
            'status'            =>  $this->status,
            'created_at'        =>  $this->created_at,
            'actions'           =>  [
                'update'    =>  route('em.trips.update', $this->id),
                'delete'    =>  route('em.trips.destroy', $this->id)
            ]
        ];
    }
}
