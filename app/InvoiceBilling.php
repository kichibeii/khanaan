<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceBilling extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function rajaOngkir()
    {
        return RajaOngkir::detail($this->city_id, $this->subdistrict_id);
                 
    }
}
