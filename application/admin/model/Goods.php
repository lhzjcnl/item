<?php

namespace app\admin\model;

class Goods extends Main
{
    protected $hidden=['create_time','update_time'];
    public function MainImg($v=0){
        $data=$this->hasMany('GoodsMainImgs');
        return $this->change_data($data,$v);
    }
    public function FirstPrice(){
        $data=$this->hasMany('GoodsPriceNums');
        $data=$data->find()['price'];
        return $data;
    }
    public function num(){//总库存
        $data=$this->hasMany('GoodsPriceNums');
        $num=0;
        foreach ($data->select() as $v){
            $num+=$v['num'];
        }
        return $num;
    }
    public function Size($val=0){
        $data=$this->hasMany('GoodsSizes');
        if($val!=0){
            $data=$data->where('id',$val)->value('size');
        }else{
            $data=$this->change_data($data,0);
        }
        return $data;
    }
    public function StyleName(){
        $data=$this->hasMany('GoodsStyleNames');
        return $data->find();
    }
    public function Style($val=0){
        $data=$this->hasMany('GoodsStyles');
        if($val!=0){
            $data=$data->where('id',$val)->value('style');
        }else{
            $data=$this->change_data($data,0);
        }
        return $data;
    }
    public function Img(){
        $data=$this->hasMany('GoodsImgs');
        return $this->change_data($data,0);
    }
    public function PriceNum(){
        return $this->hasMany('GoodsPriceNums');
    }
    protected function change_data($data,$v){
        $data=$data->order('sort');
        if($v){
            $data=$data->find();
        }
        return $data;
    }
}
