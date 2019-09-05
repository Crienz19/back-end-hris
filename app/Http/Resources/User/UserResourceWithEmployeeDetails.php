<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Employee\EmployeeResourceWithCompleteDetails;

class UserResourceWithEmployeeDetails extends JsonResource
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
            'email'         =>  $this->email,
            'isActivated'   =>  $this->isActivated,
            'isFilled'      =>  $this->isFilled,
            'profile_image' =>  $this->profile_image,
            'role'          =>  $this->role,
            'created_at'    =>  $this->created_at,
            'employee'      =>  new EmployeeResourceWithCompleteDetails($this->employee)
        ];
    }
}
