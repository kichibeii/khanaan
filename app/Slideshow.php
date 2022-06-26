<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function arrLogo()
    {
        return [
            1 => 'khanaan.png',
            2 => 'dear-asa.png',
        ];
    }

    public function metas()
    {
        return $this->hasMany('App\SlideshowMeta');
    }

    public static function getImage($slideshow)
    {
        
        if (!is_null($slideshow->image)) {
            return $slideshow->image;
        } else {
            return 'noimage.jpg';
        }
    }
}
