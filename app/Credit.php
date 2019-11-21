<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id',
        'VL',
        'SL',
        'OT',
        'OB',
        'PTO',
        'total_VL',
        'total_SL',
        'total_PTO'
    ];

    public function format()
    {
        return [
            'id'            =>  $this->id,
            'user_id'       =>  $this->user_id,
            'VL'            =>  $this->VL,
            'SL'            =>  $this->SL,
            'OT'            =>  $this->OT,
            'PTO'           =>  $this->PTO,
            'total_VL'      =>  $this->total_VL,
            'total_SL'      =>  $this->total_SL,
            'total_PTO'     =>  $this->total_PTO
        ];
    }
}
