<?php

use think\migration\Seeder;

class GoodsPriceNums extends Seeder
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
        for ($i = 0; $i < 30; $i++) {
            for ($a=1;$a<=3;$a++){
                for ($b=1;$b<=3;$b++){
                    $rows[] = [
                        'goods_id'      => $i+1,
                        'goods_styles_id'=>$i*3+$a,
                        'goods_sizes_id'=>$i*3+$b,
                        'num'=>100,
                        'price'=>$b*1000
                    ];
                }
            }
        }
        (new \app\admin\model\GoodsPriceNums())->saveAll($rows);
    }
}