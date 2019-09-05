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
}
