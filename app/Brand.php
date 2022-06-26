<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function getDataArray($empty_option=false, $search='')
    {
        $where = [];
        $where[] = ['status', 1];
        if (!empty($search)){
            $where[] = ['name', 'LIKE', '%'.$search.'%'];   
        }

        $rows = self::select('id', 'name')->where($where)->orderBy('name', 'ASC')->get();
        $arr = [];
        if ($empty_option){
            $arr[] = '';
        }
        if (count($rows)){
            foreach ($rows as $row){
                $arr[$row->id] = $row->name;
            }
        }

        return $arr;
    }
}
