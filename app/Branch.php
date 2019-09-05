<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'display_name'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee', 'branch_id', 'id');
    }
}
