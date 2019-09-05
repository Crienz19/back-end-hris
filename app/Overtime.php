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

    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'user_id');
    }
}
