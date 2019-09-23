<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Branch\BranchResource;
use App\Http\Resources\Department\DepartmentResource;

class EmployeeResourceWithCompleteDetails extends JsonResource
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
            'first_name'        =>  ucfirst($this->first_name),
            'middle_name'       =>  ucfirst($this->middle_name),
            'last_name'         =>  ucfirst($this->last_name),
            'birth_date'        =>  $this->birth_date,
            'civil_status'      =>  $this->civil_status,
            'present_address'   =>  $this->present_address,
            'permanent_address' =>  $this->permanent_address,
            'contact_no_1'      =>  $this->contact_no_1,
            'contact_no_2'      =>  $this->contact_no_2,
            'tin'               =>  $this->tin,
            'sss'               =>  $this->sss,
            'pagibig'           =>  $this->pagibig,
            'philhealth'        =>  $this->philhealth,
            'employee_id'       =>  $this->employee_id,
            'date_hired'        =>  $this->date_hired,
            'skype_id'          =>  $this->skype_id,
            'position'          =>  $this->position,
            'branch'            =>  new BranchResource($this->branch),
            'department'        =>  new DepartmentResource($this->department),
            'credit'            =>  new CreditResource($this->credit),
            'user'              =>  new UserResource($this->user)
        ];
    }
}
