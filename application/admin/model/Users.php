<?php

namespace app\admin\model;

use think\facade\Cookie;

class Users extends Main
{
    protected $hidden=['create_time','update_time','password'];
    public function login($data){
        $value='';
        $user=Users::where('name',$data['name'])->find();
        if($user!=null){
            if($user['password']==$data['password']){
//                $user=json_encode($user);
                Cookie::set('user',$user,3600);
                Cookie::set('user_name',$data['name'],3600);
            }else{
                $value='密码错误';
            }
        }else{
            $value='用户不存在';
        }
        return $value;
    }
    public function shopCarts(){
        return $this->hasMany('ShopCarts');
    }
}
