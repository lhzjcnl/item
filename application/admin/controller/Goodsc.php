<?php

namespace app\admin\controller;

use app\admin\model\Banners;
use app\admin\model\Goods;
use app\admin\model\GoodsImgs;
use app\admin\model\GoodsMainImgs;
use app\admin\model\GoodsPriceNums;
use app\admin\model\GoodsSizes;
use app\admin\model\GoodsStyleNames;
use app\admin\model\GoodsStyles;
use app\admin\model\GoodsTypes;
use think\Controller;
use think\facade\Session;
use think\Request;

class Goodsc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    protected $middleware = [
        'del'     => ['only'   => ['delall','delgoods','del'] ],
    ];
    public function index($name='',$start=0,$type=0)
    {
        $goods=new Goods();
        if($name!=''){
            $goods=$goods->where('name','like','%'.$name.'%');
        }
        if($start==1||$start==2){
            $goods=$goods->where('start',$start);
        }else{
            $start=0;
        }
        if($type!=0){
            $goods=$goods->where('goods_types_id',$type);
        }
        $goods=$goods->order('sort')->paginate(10);
        if(Session::has('err')){
            $this->assign('err',Session::get('err'));
        }
        $types=GoodsTypes::order('sort')->select();
        $this->assign('types',$types);
        $this->assign('type',$type);
        $this->assign('name',$name);
        $this->assign('start',$start);
        $this->assign('data',$goods);
        return $this->fetch('/goods/goods');
    }
    public function change_start(Request $request){
        $data=Goods::where('id',$request->param('id'))->update(['start'=>$request->param('start')]);
//        dump($data->buildSql());exit();
        return $data;
    }
    public function add(){
        $types=GoodsTypes::select()->order('sort');
        $sort=Goods::select()->count();
        $this->assign('types',$types);
        $this->assign('sort',$sort);
        return $this->fetch('/goods/goods_add');
    }
    public function toadd(Request $request){
        $imgs=$request->file();
        $data=[];
//        dump($imgs);
//        dump($request->param());exit();
        $id=Goods::create([
            'name'=>$request->param('name'),
            'goods_types_id'=>$request->param('goods_types_id'),
            'sort'=>$request->param('sort')
        ])['id'];
        foreach (['main_imgs','imgs'] as $d){
            foreach ($imgs[$d] as $key=>$file) {
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move('./uploads');
                if ($info) {
                    $data[$d][$key]['url']='/uploads/'.$info->getSaveName();
                    $data[$d][$key]['sort']=$key+1;
                    $data[$d][$key]['goods_id']=$id;
                } else {
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        }
//        dump($data);exit();
        (new GoodsImgs())->saveAll($data['imgs']);
        (new GoodsMainImgs())->saveAll($data['main_imgs']);
        foreach ($request->param('size') as $k=>$v){
            GoodsSizes::create([
                'size'=>$v,
                'goods_id'=>$id,
                'sort'=>$k+1
            ]);
        }
        GoodsStyleNames::create([
            'style_name'=>$request->param('style_name'),
            'size_name'=>$request->param('size_name'),
            'goods_id'=>$id
        ]);
        foreach ($request->param('style') as $k=>$v){
            GoodsStyles::create([
                'style'=>$v,
                'goods_id'=>$id,
                'sort'=>$k+1
            ]);
        }
        return redirect('/admin/goods/numPrice/'.$id);
    }
    public function numPrice($id){
        $goods=Goods::find($id);
        $PriceNum=$goods->PriceNum;
//        dump($PriceNum);exit();
        if(count($PriceNum)>0){
            $goodsPriceNum=[];
            foreach ($PriceNum as $v){
                $goodsPriceNum[$v['goods_styles_id']][$v['goods_sizes_id']]=[
                    'price'=>$v['price'],
                    'num'=>$v['num'],
                ];
            }
            $this->assign('goodsPriceNum',$goodsPriceNum);
        }
//        dump($goodsPriceNum[4][1]);exit();
        $style_name=$goods->StyleName();
        $goods_size=$goods->Size;
        $goods_style=$goods->Style;
        $this->assign('style_name',$style_name);
        $this->assign('goods_size',$goods_size);
        $this->assign('goods_style',$goods_style);
        $this->assign('id',$id);
//        dump($goods->num());exit();
        return $this->fetch('goods/goods_num_price');
    }
    public function toNumSave(Request $request){
        $key=$request->param('key');
        $data=[];
        foreach ($key as $k=>$v){
            $d=explode('_',$v);
            $data[$k]=[
                    'goods_id'=>$request->param('id'),'goods_styles_id'=>$d[0],
                    'goods_sizes_id'=>$d[1],'num'=>$request->param('num_'.$v),
                    'price'=>$request->param('price_'.$v),
                ];
        }
        $val=(new GoodsPriceNums())->saveAll($data);
        return redirect('/admin/goods/screen');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function delall(Request $request)
    {
        $data=$request->param('data');
        $err=1;
        foreach ($data as $id){
            $err=$this->delgoods($id);
        }
        return $err;
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
    public function edit($id=0)
    {
        $data=Goods::find($id);
       if($id!=0&&$data!=null){
           $data->MainImg;
           $data->Img;
           $data->Size;
           $data['style_name']=$data->StyleName();
           $data->PriceNum;
           $data->Style;
//           dump($data);exit();
           $this->assign('data',$data);
           $this->assign('id',$id);
           $types=GoodsTypes::select()->order('sort');
           $this->assign('types',$types);
           return $this->fetch('goods/goods_edit');
       }else{
           return redirect('/admin/goods/add');
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
        $imgs=$request->file();
        $val=$request->param();
        (new GoodsStyleNames())->save($request->only(['size_name','style_name']),['goods_id'=>$val['id']]);
        (new Goods())->save($request->only(['name','goods_types_id','sort']),['id'=>$val['id']]);
        $data=[];
        foreach (['main_imgs','imgs'] as $d){
            if(count($imgs[$d])>0){
                foreach ($imgs[$d] as $key=>$file) {
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->move('./uploads');
                    if ($info) {
                        $data[$d][$key]['url']='/uploads/'.$info->getSaveName();
                        $data[$d][$key]['sort']=$key+1;
                        $data[$d][$key]['goods_id']=$val['id'];
                    } else {
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                if($d=='imgs'){
                    (new GoodsImgs())->saveAll($data['imgs']);
                }elseif ($d=='main_imgs'){
                    (new GoodsMainImgs())->saveAll($data['main_imgs']);
                }
            }
        }
        foreach (['main_imgs','imgs'] as $d){
            if(isset($val['old'.$d])){
                foreach ($val['old'.$d] as $v){
                    if($d=='main_imgs'){
                        GoodsMainImgs::where('url',$v)->delete();
                    }elseif($d=='imgs'){
                        GoodsImgs::where('url',$v)->delete();
                    }
                    if(file_exists('.'.$v)){//判断图片是否存在
                        unlink('.'.$v);//删除
                    }
                }
            }
        }
        $value['style']=GoodsStyles::where('goods_id',$val['id'])->select();
        $value['size']=GoodsSizes::where('goods_id',$val['id'])->select();
        foreach (['size','style'] as $d){
            foreach ($val[$d] as $k=>$v){
                if(!isset($value[$d][$k])){
                    if ($d=='size'){
                        $value[$d][$k]=new GoodsSizes();
                    }elseif ($d=='style'){
                        $value[$d][$k]=new GoodsStyles();
                    }
                    $value[$d][$k]['goods_id']=$val['id'];
                    $value[$d][$k]['sort']=$k+1;
                }
                $value[$d][$k][$d]=$v;
            }
        }
        foreach ($value as $val){
            foreach ($val as $v){
                $v->save();
            }
        }
        return redirect('/admin/goods')->with('err','修改成功');
    }
    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function del(Request $request)
    {
        $err=$this->delgoods($request->param('id'));
        return redirect('/admin/goods/screen')->with('err',$err);
    }
    public function delgoods($id){
        $data=Goods::find($id);
        $banner=Banners::find($data->id);
        if($banner){
            return '该商品已绑定banner不能删除,请先修改后再删除';
        }
        $value=[];
        $value['img']=$data->Img;
        $value['main_img']=$data->MainImg;
        $value['size']=$data->Size;
        $value['style_name'][0]=$data->StyleName();
        $value['price_num']=$data->PriceNum;
        $value['style']=$data->Style;
        $data=Goods::where('id',$id)->delete();
        foreach (['img','main_img'] as $v){
            foreach ($value[$v] as $data){
                if(file_exists('.'.$data['url'])){
                    unlink('.'.$data['url']);
                }
            }
        }
        if($data){
            foreach ($value as $v){
                foreach ($v as $item) {
                    $item->delete();
                }
            }
        }
        return '删除成功';
    }
}
