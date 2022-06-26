<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropdownItem extends Model
{
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function dropdown()
    {
        return $this->belongsTo('App\Dropdown');
    }

    public function getImage()
    {
        if (!is_null($this->image)) {
            return $this->image;
        } else {
            return 'noimage.jpg';
        }
    }
}
