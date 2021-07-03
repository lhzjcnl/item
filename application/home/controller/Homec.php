<?php

namespace app\home\controller;

use app\admin\model\Admins;
use app\admin\model\Banners;
use app\admin\model\Goods;
use app\admin\model\GoodsLists;
use app\admin\model\Navs;
use app\admin\model\Users;
use app\admin\validate\Userv;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;
use think\Request;

class Homec extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->listDate();
        $this->navData();
        $banner=Banners::select()->order('sort');
        $first='';
        foreach ($banner as $v){
            $first=$v;
            break;
        }
        $goods2=Goods::where('goods_types_id',2)->where('start',1)->all();
        foreach ($goods2 as $v){
            $v['main_img']=$v->MainImg(1)['url'];
            $v['price']=$v->FirstPrice();
        }
        $this->assign('banner',$banner);
        $this->assign('first',$first);
        $this->assign('goods2',$goods2);
        return $this->fetch('/index');
    }
    public function login(){//登录页
        $token=Cookie::get('user_token');
        $user=Users::where('token',$token)->find();
        if($user!=null){
            return redirect('/');
        }
        $data=[];
        if(Session::has('data')){
            $data['data']=Session::get('data');
        }
        if(Session::has('err')){
            $this->assign('err',Session::get('err'));
        }
        return $this->fetch('/login');
    }
    public function reg(){//注册页
        if(Session::has('err')){
            $this->assign('err',Session::get('err'));
        }
        return $this->fetch('/reg');
    }
    public function toreg(Request $request){//注册提交
        $userv=new Userv();
        $data=$request->param();
        if($userv->check($data)){
            if($data['password']==$data['repeat_password']){
                $user=Users::where('name',$data['name'])->find();
                if($user==null){
                    $data=$request->only(['token','name','password']);
                    $data['password']=md5($data['password']);
                    Users::create($data);
                    return redirect('/login')->with('err','注册成功请登录');
                }else{
                    return redirect('/reg')->with('err','用户名已存在');
             }
            }else{
                return redirect('/reg')->with('err','两次密码不一致');
            }
        }else{
            return redirect('/reg')->with('err',$userv->getError());
        }
    }
    public function tologin(Request $request){//登录提交
        $userv=new Userv();
        if($userv->check($request->param())){
            $request->password=md5($request->password);
            $err=(new Users())->login($request->param());
            if($err==''){
                return redirect('/');
            }
        }else{
            $err=$userv->getError();
        }
        return redirect('/login')->with('err',$err);
    }
    public function logout(){
        Cookie::delete('user');
        Cookie::delete('user_name');
        echo "<script>alert('退出成功')
                location='/';
            </script>";
    }
    public function navData(){
        //导航数据
        $nav=Navs::order('sort')->select();
        foreach ($nav as $n){
            $type=$n->Type();
            $n['name']=$type['name'];
            $n['goods']=$type->Goods;
            foreach ($n['goods'] as $goods){
                $goods['price']=$goods->FirstPrice();
                $goods['img']=$goods->MainImg(1)['url'];
            }
        }
//        exit();
//        dump($nav);exit();
        $this->assign('nav',$nav);
    }
    public function listDate(){
        //头部商品列表数据
        $list=GoodsLists::select()->order('sort');
        foreach ($list as $d){
            $type=$d->Type();
            $d['name']=$type['name'];
            $d['goods']=$type->Goods;
            foreach ($d['goods'] as $g){
                $g['img']=$g->MainImg(1)['url'];
            }
        }
        $this->assign('list',$list);
    }
//    public function goods($id=0){
//        $this->listDate();
//        $this->navData();
//        if($id!=0){
//            $data=Goods::find($id);
//            $price=$data->PriceNum;
//            $pricearr=[];
//            foreach ($price as $item) {
//                $pricearr[$item['goods_styles_id']][$item['goods_sizes_id']]=$item['price'];
//            }
//            $pricearr=json_encode($pricearr);
//            $this->assign('price',$pricearr);
//            $this->assign('data',$data);
//            return $this->fetch('/goods');
//        }else{
//            return redirect('/');
//        }
//    }
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
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
