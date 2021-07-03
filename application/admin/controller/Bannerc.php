<?php

namespace app\admin\controller;

use app\admin\model\Banners;
use app\admin\model\Goods;
use app\admin\model\GoodsTypes;
use think\Controller;
use think\Request;

class Bannerc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $banner=Banners::select();
        $this->assign('data',$banner);
        return $this->fetch('/banner/banner');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $count=Banners::count()+1;
        $types=GoodsTypes::select()->order('sort');//获取商品分类
        $goods=Goods::where('start',1)->select()->order('sort');//获取商品
        foreach ($goods as $g){
            $arr[$g->goods_types_id][$g->id]=$g->name;
            //循环把通过分类的商品保存到下标为分类id下
            //可直接根据分类id和商品id找到该name
        }
        $arr=json_encode($arr);//数组转换成json字符串
        $this->assign('count',$count);
        $this->assign('goods',$arr);
        $this->assign('types',$types);
        return $this->fetch('/banner/banner_add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $file=$request->file('img');
        $img=$this->saveImg($file);
//        $info = $file->move('./uploads');
//        if ($info) {
//            $img='/uploads/'.$info->getSaveName();
//        } else {
//            // 上传失败获取错误信息
//            echo $file->getError();
//        }
        Banners::create([
            'img'=>$img,
            'goods_id'=>$request->param('goods'),
            'url'=>'/goods?id='.$request->param('goods'),
            'sort'=>$request->param('sort')
        ]);
        return redirect('/admin/banner');
    }
    protected function saveImg($img){
        $info = $img->move('./uploads');
        if ($info) {
            return '/uploads/'.$info->getSaveName();
        } else {
            // 上传失败获取错误信息
            echo $img->getError();
        }
    }
    public function bannerGoods($goods,$id=0){
        //判断该商品是否已关联banner

        $banner=Banners::where('goods_id',$goods)->find();
        $data=0;
        if($banner==null){
            $data=1;
        }else if($banner['id']==$id){
            $data=1;
        }
        return $data;
    }
    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delall(Request $request)
    {
        $data=Banners::all($request->param('data'));
        foreach ($data as $item) {
            if(file_exists('.'.$item['img'])){
                unlink('.'.$item['img']);
            }
        }
        $data=Banners::destroy($request->param('data'));
        return $data;
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    protected function bannerdata(){
        $types=GoodsTypes::select()->order('sort');//获取商品分类
        $goods=Goods::where('start',1)->select()->order('sort');//获取商品
        foreach ($goods as $g){
            $arr[$g->goods_types_id][$g->id]=$g->name;
            //循环把通过分类的商品保存到下标为分类id下
            //可直接根据分类id和商品id找到该name
        }
        $this->assign('goodsdata',$arr);
        $arr=json_encode($arr);//数组转换成json字符串
        $this->assign('goods',$arr);
        $this->assign('types',$types);
    }
    public function edit($id=0)
    {
        if($id){
            $this->bannerdata();
            $banner=Banners::find($id);
            $goods=Goods::find($banner->goods_id);
//            dump($type);exit();
            $banner['goods_types_id']=$goods->goods_types_id;
            $this->assign('data',$banner);
            return $this->fetch('banner/banner_edit');
        }else{
            return redirect('/admin/banner/create');
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
//        dump($request->param());exit();
        $data=$request->param();
        $img=$request->file();
        if(count($img)>0){
            $img=$this->saveImg($img['img']);
            $oldimg=Banners::find($data['id'])['img'];
//            dump($img);exit();
            if(file_exists('.'.$oldimg)){
                unlink('.'.$oldimg);
            }
        }
        Banners::where('id',$data['id'])->update([
           'sort'=>$data['sort'],
           'img'=>$img,
           'url'=>'/goods?id='.$data['goods'],
           'goods_id'=>$data['goods'],
        ]);
        return redirect('/admin/banner');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function del($id)
    {
        $data=Banners::find($id);
        if(file_exists('.'.$data['img'])){
            unlink('.'.$data['img']);
        }
        $data=$data->delete();
        return $data;
    }
}
