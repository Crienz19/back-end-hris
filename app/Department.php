<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'supervisor_id'
    ];

    public function format()
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'display_name'  =>  $this->display_name,
            'supervisor_id' =>  $this->supervisor_id,
            'employee'      =>  $this->employee,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee', 'supervisor_id', 'user_id');
    }
}
