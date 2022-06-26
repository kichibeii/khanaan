<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = [
        'id'
    ];

    public function color_group_color() {
        return $this->belongsToMany('App\ColorGroup', 'color_group_color');
    }

    public static function getData($isArray=false)
    {
        if (!$isArray){
            return Color::select('id', 'title', 'color_hex')
                ->where('status', 1)
                ->orderBy('sort_order', 'ASC')
                ->get();
        } else {
            return Color::where('status', 1)
                ->orderBy('sort_order', 'ASC')
                ->pluck('title', 'id')
                ->toArray();
        }
    }
}
