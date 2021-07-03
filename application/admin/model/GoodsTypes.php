<?php

namespace app\admin\model;


class GoodsTypes extends Main
{
    public function Goods(){
        $data=$this->hasMany('Goods');
        return $data->where('start',1)->limit(20);
    }
}
