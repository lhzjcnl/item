<?php

namespace app\admin\model;

use think\facade\Cookie;
use think\Model;

class Main extends Model
{
    protected $hidden=['create_time','update_time','password'];
}
