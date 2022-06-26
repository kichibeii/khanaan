<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;

    public function products() {
        return $this->belongsToMany('App\Product');
    }

    public static function setTag($tagInputs, $title)
    {
        /*
        $spliceTags = explode(',', $tagInputs);
        $spliceTags = array_map('trim', $spliceTags);
        */
        $spliceTags = $tagInputs;

        $arrTags = [];
        $tagTitle = false;
        foreach ($spliceTags as $tag){
            if ($tag == $title){
                $tagTitle = true;
            } else {
                $arrTags[] = $tag;
            }
        }

        if (!$tagTitle){
            $arrTags[] = $title;
        }
        return $arrTags;
        return implode(',', $arrTags);
    }
}
