<?php

namespace app\admin\controller;

use app\admin\model\Orders;
use think\Controller;
use think\facade\Session;
use think\Request;

class Orderc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $order=Orders::order('id')->paginate(5);
        foreach ($order as $v){
            $v->goods;
            $v['user']=$v->User();
            $v['goods']['img']=$v['goods']->MainImg(1)['url'];
            $v['goods']['price']=$v['goods']->FirstPrice();
            $v['goods']['size']=$v['goods']->Size($v->goods_sizes_id);
            $v['goods']['style']=$v['goods']->Style($v->goods_styles_id);
            $v['goods']['style_name']=$v['goods']->StyleName();
        }
        $this->assign('data',$order);
        return $this->fetch('order/order');
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
