<?php

use think\migration\Seeder;

class GoodsStyleNames extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create('zh_CN');
        $rows = [];
        for ($i = 1; $i <= 30; $i++) {
                $rows[] = [
                    'style_name'      => '类型',
                    'size_name'=>'内存',
                    'goods_id'=>$i
                ];
        }
        (new \app\admin\model\GoodsStyleNames())->saveAll($rows);
    }
}