<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name'
    ];

    public function format()
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'display_name'  =>  $this->display_name,
            'created_at'    =>  $this->created_at->toDayDateTimeString()
        ];
    }
}
