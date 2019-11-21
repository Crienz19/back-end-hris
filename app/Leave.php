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

    public function format()
    {
        return [
            'id'                    =>  $this->id,
            'user_id'               =>  $this->user_id,
            'type'                  =>  $this->type,
            'pay_type'              =>  $this->pay_type,
            'from'                  =>  $this->from,
            'to'                    =>  $this->to,
            'time_from'             =>  $this->time_from,
            'time_to'               =>  $this->time_to,
            'reason'                =>  $this->reason,
            'count'                 =>  $this->count,
            'recommending_approval' =>  $this->recommending_approval,
            'final_approval'        =>  $this->final_approval,
            'created_at'            =>  $this->created_at->toDayDateTimeString()
        ];
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
