<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStokActivity extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
