<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProductBooked extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
