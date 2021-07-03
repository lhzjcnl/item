<?php

namespace app\http\middleware;

use app\admin\model\Users;
use think\facade\Cookie;

class User
{
    public function handle($request, \Closure $next)
    {
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $user=Users::where('token',$users['token'])->find();
        if($user!=null){
            Cookie::set('user',$user,3600);
            Cookie::set('user_name',$user->name,3600);
            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
