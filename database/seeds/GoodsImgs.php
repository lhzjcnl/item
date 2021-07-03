<?php

use think\migration\Seeder;

class GoodsImgs extends Seeder
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
                    'url'      => '/img/goods_img1.jpg',
                    'goods_id'=>$i,
                    'sort'=>1
                ];
        }
        (new \app\admin\model\GoodsImgs())->saveAll($rows);
    }
}