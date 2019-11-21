<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class COE extends Model
{
    protected $fillable = [
        'user_id',
        'date_needed',
        'reason',
        'compensation',
        'status'
    ];

    public function format()
    {
        return [
            'id'            =>  $this->id,
            'user_id'       =>  $this->user_id,
            'date_needed'   =>  $this->date_needed,
            'reason'        =>  $this->reason,
            'compensation'  =>  $this->compensation,
            'status'        =>  $this->status,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id');
    }
}
