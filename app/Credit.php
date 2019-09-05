<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id',
        'VL',
        'OT',
        'OB',
        'PTO',
        'total_VL',
        'total_SL',
        'total_PTO'
    ];
}
