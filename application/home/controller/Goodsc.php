<?php

namespace app\home\controller;

use app\admin\model\Goods;
use app\admin\model\GoodsPriceNums;
use think\Controller;
use think\fartde\Session;
use think\Request;

class Goodsc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($id=0)
    {
        $this->data();
        $data=Goods::where('id',$id)->find();
        $data->MainImg;
        $data['first_main_img']=$data->MainImg(1)->url;
        $data['stylename']=$data->StyleName();
        if($id!=0&&$data!=null){
            $price=$data->PriceNum;
            $pricearr=[];
            foreach ($price as $item) {
                $pricearr[$item['goods_styles_id']][$item['goods_sizes_id']]=$item['price'];
            }
            $pricearr=json_encode($pricearr);
            $with_goods=Goods::where('goods_types_id',$data->goods_types_id)->where('start',1)->whereNotIn('id',$id)->select();
            if($with_goods){
                foreach ($with_goods as $goods){
                    $goods['price']=$goods->FirstPrice();
                    $goods['img']=$goods->MainImg(1)['url'];
                }
                $this->assign('with_goods',$with_goods);
            }
            $this->assign('price',$pricearr);
            $this->assign('data',$data);
//            dump($data);exit();
            return $this->fetch('/goods');
        }else{
            return redirect('/');
        }
    }
    public function data(){
        $home=new Homec();
        $home->listDate();
        $home->navData();
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function search($text='')
    {
        $this->data();
        if($text!=''){
            $goods=Goods::where('name','like','%'.$text.'%')->where('start',1)->paginate(15);
        }else{
            return redirect('/');
        }
        foreach ($goods as $v){
            $v['main_img']=$v->MainImg(1)['url'];
            $v['price']=$v->FirstPrice();
        }
        $this->assign('text',$text);
        $this->assign('data',$goods);
        return $this->fetch('/search');
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
