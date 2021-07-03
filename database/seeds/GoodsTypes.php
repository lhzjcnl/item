<?php

use think\migration\Seeder;

class GoodsTypes extends Seeder
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
        $name=['衣服','裤子','鞋子','电脑','手机','化妆品','运动器械','家电','帽子','笔记本'];
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'name'=> $name[$i],
                'sort'=>$i+1
            ];
        }
        (new \app\admin\model\GoodsTypes())->saveAll($rows);
    }
}