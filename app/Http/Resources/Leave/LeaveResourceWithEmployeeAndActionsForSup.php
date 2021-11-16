<?php

namespace App\Http\Resources\Leave;

use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResourceWithEmployeeAndActionsForSup extends JsonResource
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
            'id'                    =>  $this->id,
            'type'                  =>  $this->type,
            'pay_type'              =>  $this->pay_type,
            'from'                  =>  $this->from,
            'to'                    =>  $this->to,
            'time_from'             =>  $this->time_from,
            'time_to'               =>  $this->time_to,
            'reason'                =>  $this->reason,
            'count'                 =>  $this->count,
            'recommending_approval' =>  $this->recommending_approval,
            'final_approval'        =>  $this->final_approval,
            'created_at'            =>  $this->created_at->toDateTimeString(),
            'employee'              =>  new EmployeeResource($this->employee),
            'actions'               =>  [
                'approve'      =>  route('sv.leaves.approve', $this->id),
                'disapprove'   =>  route('sv.leaves.disapprove', $this->id)
            ]
        ];
    }
}
