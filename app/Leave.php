<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'pay_type',
        'from',
        'to',
        'time_from',
        'time_to',
        'reason',
        'count',
        'recommending_approval',
        'final_approval'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id');
    }

    public function employeeByDepartment()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id')
            ->where('department_id', Department::where('supervisor_id', auth()->user()->id)->first()->id);
    }
}
