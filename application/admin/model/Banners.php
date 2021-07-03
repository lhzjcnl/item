<?php

namespace app\admin\model;

class Banners extends Main
{
    public function goodsNames(){
        $data=$this->belongsTo('Goods');
        return $data->find()['name'];
    }
}
