<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayList extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'uri',
    ];

    public function songs()
    {
        return $this->hasMany('App\Models\Song');
    }
}
