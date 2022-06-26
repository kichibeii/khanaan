<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceTracking extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
