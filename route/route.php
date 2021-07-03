<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});
Route::get('hello/:name', 'index/hello');
Route::get('admin/logout','admin/adminc/logout');
Route::get('admin/login','admin/adminc/login');
Route::post('admin/tologin','admin/adminc/tologin');
Route::get('admin/back','admin/adminc/back');
Route::get('admin/backdel','admin/adminc/backdel');
Route::group('admin',function (){
    Route::get('/','orderc/index');
    Route::group('goods',function (){
//        Route::get('/','index');
        Route::get('screen','index');
        Route::post('change_start','change_start');
        Route::post('del','del');
        Route::get('add','add');
        Route::get('edit','edit');
        Route::get('numPrice/:id','numPrice');
        Route::post('toNumSave','toNumSave');
        Route::post('toadd','toadd');
        Route::post('update','update');
        Route::post('delall','delall');
    })->prefix('admin/goodsc/');
    Route::group('goods_type',function (){
        Route::get('/','index');
        Route::get('edit','edit');
        Route::post('save','save');
        Route::post('delall','delall');
        Route::post('del','del');
    })->prefix('admin/goods_typec/');
    Route::get('goods_type/edit','Goods_typec/edit');
    Route::group('banner',function (){
        Route::get('/','index');
        Route::get('create','create');
        Route::get('edit','edit');
        Route::post('save','save');
        Route::post('update','update');
        Route::post('delall','delall');
        Route::post('del','del');
        Route::post('bannerGoods/:goods','bannerGoods');
    })->prefix('admin/bannerc/');
    Route::group('nav',function (){
        Route::get('/','index');
        Route::get('create','create');
        Route::get('edit','edit');
        Route::post('save','save');
        Route::post('update','update');
        Route::post('delete/:id','delete');
        Route::post('delall','delall');
        Route::post('navTypes/:type','navTypes');
    })->prefix('admin/navc/');
    Route::group('goods_list',function (){
        Route::get('/','index');
        Route::get('create','create');
        Route::get('edit','edit');
        Route::post('save','save');
        Route::post('update','update');
        Route::post('delete/:id','delete');
        Route::post('delall','delall');
        Route::post('GoodsList/:type','GoodsList');
    })->prefix('admin/goods_listc/');
    Route::get('admin','adminc/index');
    Route::get('edit','adminc/edit');
    Route::post('change','adminc/change');
    Route::post('delete','adminc/delete');
})->prefix('admin/')->middleware('Check');
Route::group('/',function () {
    Route::get('/','homec/index');
    Route::get('login','homec/login');
    Route::get('reg','homec/reg');
    Route::get('logout','homec/logout');
    Route::post('tologin','homec/tologin');
    Route::post('toreg','homec/toreg');
    Route::get('goods','goodsc/index');
    Route::get('search','goodsc/search');
    Route::group('user',function (){
        Route::get('/','index');
        Route::get('info','info');
        Route::get('update','update');
        Route::get('submit_order','submit_order');
        Route::post('submit/:type','submit');
        Route::post('buy','buy');
        Route::get('cart','cart');
        Route::group('cart',function (){
            Route::get('/','cart');
            Route::post('del/:id','cart_del');
            Route::post('submit','cart_submit');
        });
        Route::group('address',function (){
            Route::get('/','address');
            Route::get('edit','edit');
            Route::post('save','address_save');
            Route::post('del','address_del');
        });
        Route::post('save','save');
    })->prefix('home/userc/')->middleware('user');
})->prefix('home/');
return [

];
