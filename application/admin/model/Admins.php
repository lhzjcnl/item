<?php
namespace app\admin\model;

use think\facade\Cookie;
use think\Model;

class Admins extends Model
{
    protected $hidden=['create_time','update_time','password'];
    public function login($data){
        $value='';
        $admin=Admins::where('name',$data['name'])->find();
        if($admin!=null){
            if($admin['password']==$data['password']){
                Cookie::set('admin_token',$admin['token'],3600);
                Cookie::set('admin_name',$admin['name'],3600);
            }else{
                $value='密码错误';
            }
        }else{
            $value='用户不存在';
        }
        return $value;
    }
}
