<?php

namespace app\admin\model;


class Orders extends Main
{
    public function goods(){
        return $this->belongsTo('Goods');
    }
    public function User(){
        $data = $this->belongsTo('Users');
        return $data->value('name');
    }
}
