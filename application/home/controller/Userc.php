<?php

namespace app\home\controller;

use app\admin\model\Address;
use app\admin\model\Goods;
use app\admin\model\GoodsPriceNums;
use app\admin\model\Orders;
use app\admin\model\ShopCarts;
use app\admin\model\Users;
use app\admin\validate\Userv;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;
use think\Request;

class Userc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(){
        $this->data();
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $order=Orders::where('users_id',$users['id'])->order('id')->select();
        foreach ($order as $v){
            $v->goods;
            $v['goods']['img']=$v['goods']->MainImg(1)['url'];
            $v['goods']['price']=$v['goods']->FirstPrice();
            $v['goods']['size']=$v['goods']->Size($v->goods_sizes_id);
            $v['goods']['style']=$v['goods']->Style($v->goods_styles_id);
            $v['goods']['style_name']=$v['goods']->StyleName();
        }
        $this->assign('data',$order);
        return $this->fetch('user/user_order');
    }
    public function submit($type=0,Request $request){
        $goods[0]=Goods::find($request->param('goods_id'));
        $all_price=0;
        if($type==1){
            $value[0]=$request->only(['goods_styles_id','goods_sizes_id','goods_id']);
            $goods[0]->num=$request->param('num');
            return $this->order_goods($goods,$value);
        }else if($type==2){
            $users=\think\facade\Cookie::get('user');
            $users=json_decode($users,true);
            $request->users_id=$users['id'];
            $goods=ShopCarts::create($request->param());
            return redirect('/user/cart');
        }
    }
    public function order_goods($all_goods,$value){
        //订单商品遍历数据
        $data=(object)[];
        $data->all_price=0;
        foreach ($all_goods as $k=> $goods){
            $goods['price']=GoodsPriceNums::where($value[$k])->value('price');
            $goods['size']=$goods->Size($value[$k]['goods_sizes_id']);
            $goods['style']=$goods->Style($value[$k]['goods_styles_id']);
            $goods['style_name']=$goods->StyleName();
            $goods['img']=$goods->MainImg(1)['url'];
            $goods['total_price']=$goods['price']*$goods->num;
            $data->all_price+=$goods['total_price'];
            $value[$k]['price']=$goods['price'];
            $value[$k]['num']=$goods['num'];
            $data->goods[]=$goods;
        }
        Session::set('all_price',$data->all_price);
        Session::set('goods',$data->goods);
        Session::set('data',$value);
        return redirect('/user/submit_order');
    }
    public function cart_submit(Request $request){
        $shop_cart=ShopCarts::all($request->param('id'));
        $num=explode(',',$request->param('num'));
        foreach ($shop_cart as $k=>$v){
            $goods[$k]=Goods::find($v->goods_id);
            $goods[$k]['num']=$num[$k];
        }
//        Session::set('cart_id',$request->param('id'));
        return $this->order_goods($goods,$shop_cart);
    }
    public function cart(){
        $this->data();
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $cart=Users::find($users['id'])->shopCarts;
        $data=[];
        foreach ($cart as $c){
            $c->goods;
            $c->goods['img']=$c->goods->MainImg(1)['url'];
            $c['price']=GoodsPriceNums::where(['goods_styles_id'=>$c->goods_styles_id,'goods_sizes_id'=>$c->goods_sizes_id])->value('price');
            $c['goods']['size']=$c['goods']->Size($c->goods_sizes_id);
            $c['goods']['style']=$c['goods']->Style($c->goods_styles_id);
            $c['goods']['style_name']=$c['goods']->StyleName();
            $data[$c->id]['price']=$c->price;
            $data[$c->id]['num']=$c->num;
        }
        $data=json_encode($data);
        $this->assign('data',$data);
        $this->assign('cart',$cart);
        return $this->fetch('user/user_cart');
    }
    public function cart_del($id){
        $a=ShopCarts::find($id)->delete();
        return $a;
    }
    public function info()
    {
        $this->data();
        return $this->fetch('user/user_info');
    }
    public function data(){
        $home=new Homec();
        $home->listDate();
        $home->navData();
    }
    public function update(){
        $this->data();
        return $this->fetch('user/user_info_update');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function address_del($id)
    {
        Address::where('id',$id)->delete();
    }
    public function address(){
        $this->data();
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $address=Address::where('users_id',$users['id'])->select();
        $this->assign('data',$address);
        return $this->fetch('user/user_address');
    }
    public function submit_order(){
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $address=Address::where('users_id',$users['id'])->select();
        if(count($address)<1){
            return "<script>
                alert('你还没有收货地址请添加');
                location='/user/address';
            </script>";
        }
        $this->assign('goods',Session::get('goods'));
        $this->assign('all_price',Session::get('all_price'));
        $this->assign('data',$address);
        return $this->fetch('user/submit_order');
    }
    public function buy(Request $request){
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        $data=Session::get('data');
        $cart_id=[];
        foreach ($data as $k=>$v){
            $data[$k]['address_id']=$request->param('address_id');
            $data[$k]['users_id']=$users['id'];
            $data[$k]['state']=0;
            if(isset($v['id'])){
                $cart_id[]=$v['id'];
                unset($v['id']);
            }
        }
//        dump($data);exit();
        $data=json_decode(json_encode($data),true);
        (new Orders())->saveAll($data);
        if(count($cart_id)>0){
            ShopCarts::destroy($cart_id);
        }
        return redirect('/user');
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request){
        $v=new Userv();
        $data=$request->param();
        $data['name']=Cookie::get('user_name');
        if($v->check($data)){
            if($data['password']==$data['repeat_password']){
                $user=Users::where('name',$data['name'])->find();
                if($user!=null){
                    if($user['password']==md5($data['old_password'])){
                        $user['password']=md5($data['password']);
                        $user->save();
                        return redirect('/user');
                    }else{
                        echo "原密码错误";
                    }
                }else{
                    echo "用户不存在请重新登录";
                }
            }else{
                echo "两次密码不一致";
            }
        }else{
            dump($v->getError());
        }
    }
    public function address_save(Request $request){
        if($request->param('id')){
            $address=Address::find($request->id);
            $address->save(
                $request->only(['name','phone','address'])
            );
        }else{
            Address::create($request->param());
        }
        return redirect('/user/address');
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
    public function edit($id=0)
    {
        $v=new Userv();
        $this->data();
        $users=\think\facade\Cookie::get('user');
        $users=json_decode($users,true);
        if($id!=0){
            $address=Address::find($id);
//            dump($address);exit();
            $this->assign('data',$address);
        }
        $this->assign('users_id',$users['id']);
        return $this->fetch('user/user_address_edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */

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
