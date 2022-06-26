<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
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
        return $this->hasMany('App\StockOpnameItem', 'stock_opname_id');
    }
}
