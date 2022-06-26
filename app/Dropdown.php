<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    protected $guarded = [
        'id'
    ];

    public function items()
    {
        return $this->hasMany('App\DropdownItem');
    }

    public static function getOptions($code, $useSlug=false)
    {
        $key = $useSlug ? 'slug' : 'id';
        
        $dropdown = Dropdown::whereCode($code)->firstOrFail();
        return $dropdown->items()
            ->where('status', 1)
            ->orderBy('sort_order', 'ASC')
            ->pluck('title', $key)
            ->toArray();
    }
}
