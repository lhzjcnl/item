<?php

namespace app\admin\controller;

use app\admin\model\GoodsLists;
use app\admin\model\GoodsTypes;
use app\admin\model\Navs;
use think\Controller;
use think\Request;

class GoodsListc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        $data=GoodsLists::select()->order('sort');
        foreach ($data as $d){
            $d['name']=$d->TypeName();
        }
        $this->assign('data',$data);
        return $this->fetch('goods_list/goods_list');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $count=GoodsLists::count()+1;
        $types=GoodsTypes::select()->order('sort');//获取商品分类
        $this->assign('count',$count);
        $this->assign('types',$types);
        return $this->fetch('goods_list/goods_list_add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        GoodsLists::create([
            'sort'=>$request->param('sort'),
            'goods_types_id'=>$request->param('types'),
        ]);
        return redirect('/admin/goods_list');
    }
    public function GoodsList($type,$id=0){
        $list=GoodsLists::where('goods_types_id',$type)->find();
        $data=0;
        if($list==null){
            $data=1;
        }else if($list['id']==$id){
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
        if($id!=0){
            $types=GoodsTypes::select()->order('sort');
            $this->assign('types',$types);
            $data=GoodsLists::find($id);
            $this->assign('data',$data);
            return $this->fetch('goods_list/goods_list_edit');
        }else{
            return redirect('/admin/goods_list/create');
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
        $list=GoodsLists::find($data['id']);
        $list['sort']=$data['sort'];
        $list['goods_types_id']=$data['types'];
        (new GoodsLists())->save([
            'sort'=>$data['sort'],
            'goods_types_id'=>$data['types']
        ],['id'=>$data['id']]);
//        GoodsLists::where('id',$data['id'])->update([
//            'sort'=>$data['sort'],
//            'goods_types_id'=>$data['types']
//        ]);
        return redirect('/admin/goods_list');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $data=GoodsLists::where('id',$id)->delete();
        return $data;
    }
    public function delall(Request $request){
        $data=GoodsLists::destroy($request->param('data'));
        return $data;
    }
}
