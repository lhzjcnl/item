<?php

namespace app\admin\controller;

use app\admin\model\Admins;
use app\admin\validate\Adminv;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;
use think\Request;

class Adminc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    protected $middleware = [
        'admin'     => ['except'   => ['login','tologin','back','logout','backdel'] ],
    ];
    public function index(){
        $data=Admins::paginate(10);
        $this->assign('data',$data);
        return $this->fetch('/admin/admin');
    }
    public function change(Request $request){
        $v=new Adminv();
        if(isset($request->id)){//如果有id则是修改
            $err=$this->update($request->param());
            if($err==1){
                return redirect('/admin');
            }
        }
        if($v->check($request->param())){
            if($request->param('password')==$request->param('repeat_password')){
                $request->password=md5($request->password);
                $arr=$request->param();
                unset($arr['repeat_password']);
                (new Admins())::create($arr);
                return redirect('/admin');
            }else{
                $err='两次密码不一致';
            }
        }else{
            $err=$v->getError();
        }
        return redirect('/admin/edit')->with('err',$err)->with('olddata',$request->param());
    }
    public function edit(){
        if(Session::has('err')){
            $err=Session::get('err');
            $this->assign('err',$err);
        }
        if(Session::has('olddata')){
            $olddata=Session::get('olddata');
//            dump($olddata);exit();
            $this->assign('data',$olddata);
        }else{
            if(isset($_GET['id'])){
                $admin=Admins::find($_GET['id']);
                $this->assign('data',$admin);
            }
        }
        return $this->fetch('/admin/admin_edit');
    }
    public function login()
    {
        $token=Cookie::get('admin_token');
        $admin=Admins::where('token',$token)->find();
        if($admin!=null){
            return redirect('/admin/order');
        }
        $data=[];
        if(Session::has('data')){
            $data['data']=Session::get('data');
        }
        return view('/login',$data);
    }
    public function tologin(Request $request){
        $adminv=new Adminv();
        $data['name']=$request->param('name');
        if($adminv->check($request->param())){
            $request->password=md5($request->password);
            $data['err']=(new Admins())->login($request->param());
            if($data['err']==''){
                return redirect('/admin');
            }
        }else{
            $data['err']=$adminv->getError();
        }
        return redirect('/admin/login')->with('data',$data);
    }
    public function logout(){
        Cookie::delete('admin_name');
        Cookie::delete('admin_token');
        echo "<script>alert('退出成功')
                location='/admin/login';
            </script>";
    }
    public function back(){
        return $this->fetch('/back');
    }
    public function backdel(){
        return "你没有该权限";
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($data)
    {
        $arr=$data;
        if($arr['password']==''&&$arr['repeat_password']==$arr['password']){
            $arr['password']='123456';
            unset($data['password']);
            unset($data['repeat_password']);
        }
        $v=new Adminv();

        if($v->check($arr)){
            unset($data['id']);
            $a=Admins::where('id',$arr['id'])->update($data);
            return $a;
        }else{
            return $v->getError();
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        $a=Admins::where('id',$_GET['id'])->delete();
        return $a;
    }
}
