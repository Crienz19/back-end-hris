<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'from',
        'to',
        'reason',
        'status'
    ];

    public function format() 
    {
        return [
            'id'            =>  $this->id,
            'user_id'       =>  $this->user_id,
            'date'          =>  $this->date,
            'from'          =>  [
                'standard'  =>  date("g:i A", strtotime($this->from)),
                'other'     =>  $this->from
            ],
            'to'            =>  [
                'standard'  =>  date("g:i A", strtotime($this->to)),
                'other'     =>  $this->to
            ],
            'reason'        =>  $this->reason,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }

    public function adminFormat() 
    {
        return [
            'id'            =>  $this->id,
            'user_id'       =>  $this->user_id,
            'date'          =>  $this->date,
            'from'          =>  [
                'standard'  =>  date("g:i A", strtotime($this->from)),
                'other'     =>  $this->from
            ],
            'to'            =>  [
                'standard'  =>  date("g:i A", strtotime($this->to)),
                'other'     =>  $this->to
            ],
            'reason'        =>  $this->reason,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDayDateTimeString(),
            'employee'      =>  $this->employee
        ];
    }

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id');
    }
}
