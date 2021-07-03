<?php

use think\migration\Seeder;

class Goods extends Seeder
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
                'name'      => "$faker->text",
                'goods_types_id'=>rand(1,10),
                'sort'=>$i,
                'start'=>rand(1,2)
            ];
        }
        (new \app\admin\model\Goods())->saveAll($rows);
    }
}