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

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id');
    }
}
