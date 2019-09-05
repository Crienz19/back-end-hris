<?php

namespace App\Http\Resources\Department;

use App\Http\Resources\Employee\EmployeeResourceWithCompleteDetails;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceWithEmployeeDetails;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'name'          =>  $this->name,
            'display_name'  =>  $this->display_name,
            'supervisor'    =>  new UserResource($this->employee)
        ];
    }
}
