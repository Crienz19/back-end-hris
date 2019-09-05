<?php

namespace App\Http\Resources\Overtime;

use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResourceWithEmployeeAndActions extends JsonResource
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
            'date'      =>  $this->date,
            'from'      =>  [
                'standard'  =>  date("g:i A", strtotime($this->from)),
                'other'     =>  $this->from
            ],
            'to'        =>  [
                'standard'  =>  date("g:i A", strtotime($this->to)),
                'other'     =>  $this->to
            ],
            'reason'    =>  $this->reason,
            'status'    =>  $this->status,
            'employee'  =>  new EmployeeResource($this->employee),
            'created_at'=>  $this->created_at,
            'actions'   =>  [
                'approve'       =>  route('hr.overtimes.approve', $this->id),
                'disapprove'    =>  route('hr.overtimes.disapprove', $this->id)
            ]
        ];
    }
}
