<?php

use think\migration\Seeder;

class GoodsMainImgs extends Seeder
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
        $img='goods';
        for ($i = 1; $i <= 30; $i++) {
            for ($k=1;$k<=5;$k++){
                $rows[] = [
                    'url'      => '/img/'.$img.rand(1,8).'.png',
                    'goods_id'=>$i,
                    'sort'=>$k
                ];
            }
        }
        (new \app\admin\model\GoodsMainImgs())->saveAll($rows);
    }
}