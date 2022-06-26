<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAdditional extends Model
{
    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'uploaded_by');
    }

    public function items()
    {
        return $this->hasMany('App\ProductAdditionalItem', 'product_additional_id');
    }
}
