<?php

use think\migration\Seeder;

class GoodsSzies extends Seeder
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
        $size=['64G','128G','256G'];
        for ($i = 1; $i <= 30; $i++) {
            for ($k=0;$k<count($size);$k++){
                $rows[] = [
                    'size'      => $size[$k],
                    'goods_id'=>$i,
                    'sort'=>$k+1,
                ];
            }
        }
        (new \app\admin\model\GoodsSizes())->saveAll($rows);
    }
}