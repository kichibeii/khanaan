<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
