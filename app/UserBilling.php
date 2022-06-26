<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RajaOngkir;

class UserBilling extends Model
{
    protected $guarded = [
        'id'
    ];

    public function rajaOngkir()
    {
        return RajaOngkir::detail($this->city_id, $this->subdistrict_id);

    }

    public function rajaOngkirCountry()
    {
        return RajaOngkir::country($this->country_id);

    }
}
