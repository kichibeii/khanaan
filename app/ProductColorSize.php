<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
