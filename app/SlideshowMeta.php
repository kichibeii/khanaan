<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlideshowMeta extends Model
{
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function slideshow()
    {
        return $this->belongsTo('App\Slideshow');
    }
}
