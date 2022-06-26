<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function getValue($name)
    {
        $setting = Setting::where('name', $name)->firstOrFail();
        return $setting->values;
    }
}
