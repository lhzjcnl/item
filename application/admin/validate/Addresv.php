<?php

namespace app\admin\validate;

use think\Validate;

class Addresv extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name|收货人'=>'require|min:2|max:10',
        'phone|手机号码'=>'require|min:6|max:16',
        'address|收货地址'=>'require|min:10|max:40'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
}
