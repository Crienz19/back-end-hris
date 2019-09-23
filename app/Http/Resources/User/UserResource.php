<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;

class UserResource extends JsonResource
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
            'role'          =>  $this->role,
            'isActivated'   =>  $this->isActivated,
            'isFilled'      =>  $this->isFilled,
            'profile_image' =>  url('/') .'/images/'. $this->profile_image
        ];
    }
}
