<?php

namespace app\http\middleware;

use app\admin\model\Admins;

class Del
{
    public function handle($request, \Closure $next)
    {
        $token=\think\facade\Cookie::get('admin_token');
        $admin=Admins::where('token',$token)->find();
        if($admin['role']=='站长'){
            return $next($request);
        }else{
            return redirect('/admin/backdel');
        }
    }
}
