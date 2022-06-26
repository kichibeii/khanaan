<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
