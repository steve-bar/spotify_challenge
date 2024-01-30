<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'artists',
        'album',
        'uri',
        'name',
        'duration_ms',
        'popularity',
        'explicit',
    ];

    public function getArtistsAttribute($artists)
    {
        return json_decode($artists);
    }

    public function getAlbumAttribute($album)
    {
        return json_decode($album);
    }
}
