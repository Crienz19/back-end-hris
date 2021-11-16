<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'isActivated', 'isFilled', 'role', 'profile_image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'id');
    }

    public function leave()
    {
        return $this->hasOne('App\Leave', 'user_id', 'id');
    }

    public function overtime()
    {
        return $this->hasOne('App\Overtime', 'user_id', 'id');
    }

    public function trip()
    {
        return $this->hasOne('App\Trip', 'user_id', 'id');
    }

    public function coe()
    {
        return $this->hasOne('App\COE', 'user_id', 'id');
    }

    public function credit()
    {
        return $this->hasOne('App\Credit', 'user_id', 'id');
    }

    public function format()
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'email'         =>  $this->email,
            'role'          =>  $this->role,
            'isActivated'   =>  $this->isActivated,
            'isFilled'      =>  $this->isFilled,
            'profile_image' =>  url('/') .'/images/'. $this->profile_image,
            'created_at'    =>  $this->created_at->toDayDateTimeString(),
            'credits'       =>  $this->credit,
            'employee'      =>  $this->employee()
                ->with('branch')
                ->with('department')
                ->first()
        ];
    }

    public function baseFormat()
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'email'         =>  $this->email,
            'role'          =>  $this->role,
            'isActivated'   =>  $this->isActivated,
            'isFilled'      =>  $this->isFilled,
            'profile_image' =>  url('/') .'/images/'. $this->profile_image,
            'created_at'    =>  $this->created_at->toDayDateTimeString(),
        ];
    }
}
