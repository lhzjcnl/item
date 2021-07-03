<?php

namespace app\admin\controller;

use app\admin\model\GoodsTypes;
use app\admin\model\Navs;
use think\Controller;
use think\Request;

class Navc extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=Navs::select()->order('sort');
        foreach ($data as $d){
            $d->name=$d->TypeName();
        }
        $this->assign('data',$data);
        return $this->fetch('nav/nav');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $count=Navs::count()+1;
        $types=GoodsTypes::select()->order('sort');
        $this->assign('types',$types);
        $this->assign('count',$count);
        return $this->fetch('nav/nav_add');
    }
    public function navTypes($type,$id=0){
        $nav=Navs::where('goods_types_id',$type)->find();
        $data=0;
        if($nav==null){
            $data=1;
        }else if($nav['id']==$id){
            $data=1;
        }
        return $data;
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        Navs::create([
            'sort'=>$request->param('sort'),
            'goods_types_id'=>$request->param('types'),
        ]);
        return redirect('/admin/nav');
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
           $nav=Navs::find($id);
           $this->assign('nav',$nav);
           return $this->fetch('nav/nav_edit');
       }else{
           return redirect('/admin/nav/create');
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
        $data=$request->param();
        Navs::where('id',$data['id'])->update([
           'sort'=>$data['sort'],
            'goods_types_id'=>$data['types']
        ]);
        return redirect('/admin/nav');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $data=Navs::where('id',$id)->delete();
        return $data;
    }
    public function delall(Request $request){
        $data=Navs::destroy($request->param('data'));
        return $data;
    }
}
