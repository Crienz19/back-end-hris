<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeaveCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                        =>  $this->id,
            'type'                      =>  $this->type,
            'pay_type'                  =>  $this->pay_type,
            'from'                      =>  $this->from,
            'to'                        =>  $this->to,
            'time_from'                 =>  $this->time_from,
            'time_to'                   =>  $this->time_to,
            'reason'                    =>  $this->reason,
            'recommending_approval'     =>  $this->recommending_approval,
            'final_approval'            =>  $this->final_approval,
            'actions'                   => [
                'update'    =>  route('em.leaves.update', $this->id),
                'delete'    =>  route('em.leaves.delete', $this->id)
            ]
        ];
    }
}
