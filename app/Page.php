<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function getTitleById($id)
    {
        $page = Page::select([
            'title',
            'title_id'
        ])->whereStatus(1)->whereId($id)->first();

        if ($page){
            return getTextLang($page, 'title');
        } else {
            return '';
        }
    }
}
