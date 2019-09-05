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

    public function employee()
    {
        return $this->belongsTo('App\User', 'supervisor_id', 'id');
    }
}
