<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductHot extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
