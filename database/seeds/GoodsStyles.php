<?php

use think\migration\Seeder;

class GoodsStyles extends Seeder
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
        $style=['红色','蓝色','金色'];
        for ($i = 1; $i <= 30; $i++) {
            for ($k=0;$k<count($style);$k++){
                $rows[] = [
                    'style'      => $style[$k],
                    'goods_id'=>$i,
                    'sort'=>$k+1
                ];
            }
        }
        (new \app\admin\model\GoodsStyles())->saveAll($rows);
    }
}