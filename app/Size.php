<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $guarded = [
        'id'
    ];

    public function getImage()
    {
        
        if (!is_null($this->image)) {
            return $this->image;
        } else {
            return 'noimage.jpg';
        }
    }

    public static function getDataArray($empty_option=false, $search='')
    {
        $where = [];
        //$where[] = ['status', 1];
        if (!empty($search)){
            $where[] = ['title', 'LIKE', '%'.$search.'%'];   
        }

        $rows = self::select('id', 'title')->where($where)->orderBy('title', 'ASC')->get();
        $arr = [];
        if ($empty_option){
            $arr[] = '';
        }
        if (count($rows)){
            foreach ($rows as $row){
                $arr[$row->id] = $row->title;
            }
        }

        return $arr;
    }
}
