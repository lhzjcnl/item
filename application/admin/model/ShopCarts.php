<?php

namespace app\admin\model;


class ShopCarts extends Main
{
    public function goods(){
        return $this->belongsTo('Goods');
    }
}
