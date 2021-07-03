<?php

namespace app\http\middleware;

use app\admin\model\Admins;

class Check
{
    public function handle($request, \Closure $next)
    {
        $token=\think\facade\Cookie::get('admin_token');
        $admin=Admins::where('token',$token)->find();
        if($admin!=null){
            return $next($request);
        }else{
            return redirect('/admin/login');
        }
    }
}
