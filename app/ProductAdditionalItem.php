<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAdditionalItem extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
