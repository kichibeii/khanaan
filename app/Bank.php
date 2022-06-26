<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function getImage($bank)
    {
        
        if (!is_null($bank->image)) {
            return $bank->image;
        } else {
            return 'noimage.jpg';
        }
    }
}
