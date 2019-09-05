<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResourceWithActions extends JsonResource
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
            'id'        =>  $this->id,
            'type'      =>  $this->type,
            'pay_type'  =>  $this->pay_type,
            'from'      =>  $this->from,
            'to'        =>  $this->to,
            'time_from' =>  $this->time_from,
            'time_to'   =>  $this->time_to,
            'reason'    =>  $this->reason,
            'recommending_approval'  =>  $this->recommending_approval,
            'final_approval'        =>  $this->final_approval,
            'actions'   =>  [
                'approve'       =>  route('hr.leaves.update', $this->id),
                'disapprove'    =>  route('hr.leaves.update', $this->id)
            ]
        ];
    }
}
