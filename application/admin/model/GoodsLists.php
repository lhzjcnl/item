<?php

namespace app\admin\model;


class GoodsLists extends Main
{
    public function TypeName(){
        $data=$this->belongsTo('GoodsTypes');
        return $data->find()['name'];
    }
    public function Type(){
        $data=$this->belongsTo('GoodsTypes');
        return $data->find();
    }
}
