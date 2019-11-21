<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'civil_status',
        'present_address',
        'permanent_address',
        'contact_no_1',
        'contact_no_2',
        'tin',
        'sss',
        'pagibig',
        'philhealth',
        'employee_id',
        'date_hired',
        'branch_id',
        'skype_id',
        'department_id',
        'position'
    ];

    public function format()
    {
        return [
            'id'                =>  $this->id,
            'user_id'           =>  $this->user_id,
            'first_name'        =>  $this->first_name,
            'middle_name'       =>  $this->middle_name,
            'last_name'         =>  $this->last_name,
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
            'branch_id'         =>  $this->branch_id,
            'skype_id'          =>  $this->skype_id,
            'department_id'     =>  $this->department_id,
            'position'          =>  $this->position
        ];
    }

    public function branch()
    {
        return $this->hasOne('App\Branch', 'id', 'branch_id');
    }

    public function department()
    {
        return $this->hasOne('App\Department', 'id', 'department_id');
    }

    public function credit()
    {
        return $this->hasOne('App\Credit', 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
