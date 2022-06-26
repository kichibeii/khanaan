<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
