<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
