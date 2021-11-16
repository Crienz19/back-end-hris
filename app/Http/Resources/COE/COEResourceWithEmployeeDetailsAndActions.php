<?php

namespace App\Http\Resources\COE;

use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class COEResourceWithEmployeeDetailsAndActions extends JsonResource
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
            'employee'      =>  new EmployeeResource($this->employee),
            'reason'        =>  $this->reason,
            'date_needed'   =>  $this->date_needed,
            'compensation'  =>  $this->compensation,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDateTimeString(),
            'actions'       =>  [
                'acknowledge'    =>  route('hr.coes.acknowledge', $this->id),
            ]
        ];
    }
}
