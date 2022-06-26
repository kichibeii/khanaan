<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductNewReleases extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
