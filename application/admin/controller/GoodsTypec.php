<?php

namespace app\admin\controller;

use app\admin\model\Admins;
use app\admin\model\Goods;
use app\admin\model\GoodsTypes;
use app\admin\validate\GoodsTypev;
use think\Controller;
use think\facade\Session;
use think\Request;

class GoodsTypec extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    protected $middleware = [
        'del'     => ['only'   => ['delall','del'] ],
    ];
    public function index()
    {
        $data=GoodsTypes::order('sort')->paginate(10);
        $this->assign('data',$data);
        if(Session::has('err')){
            $this->assign('err',Session::get('err'));
        }
        return $this->fetch('/goods_type/goods_type');
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
            $err=$this->del($id);
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
        $v=new GoodsTypev();
        if(!$v->check($request->param())){
            $url='admin/goods_type/edit';
            if($request->param('id')){
               $url.='?id='.$request->param('id');
            }
            return redirect($url)->with('err',$v->getError())->with('olddata',$request->param());
        }
        if($request->param('id')){
            $g=GoodsTypes::update($request->param())->where('id',$request->param('id'));
            $err='修改成功';
        }else{
            $g=GoodsTypes::create($request->param());
            $err='添加成功';
        }
        return redirect('/admin/goods_type')->with('err',$err);
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
        $data['sort']=GoodsTypes::count()+1;
        if(Session::has('err')){
            $err=Session::get('err');
            $this->assign('err',$err);
        }
        if(Session::has('olddata')){
            $olddata=Session::get('olddata');
//            dump($olddata);exit();
            $this->assign('data',$olddata);
        }else{
            if($id!=0){
                $data=GoodsTypes::find($id);
            }
        }
        $this->assign('data',$data);
        return $this->fetch('/goods_type/goods_type_edit');
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
    public function del($id)
    {
        $goods=Goods::where('goods_types_id',$id)->count();
        if($goods>0){
            return "该分类有所属商品不能删除";
        }else{
            $b=GoodsTypes::where('id',$id)->delete();
            return $b;
        }

    }
}
