<?php

use think\migration\Migrator;
use think\migration\db\Column;

class GoodsMainImgs extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table  =  $this->table('goods_main_imgs',array('engine'=>'MyISAM'));
        $table->addColumn('url', 'string',array('limit'  =>  100,'default'=>'','comment'=>'路径'))
            ->addColumn('goods_id', 'integer',array('limit'  =>  10,'default'=>0,'comment'=>'商品id'))
            ->addColumn('sort', 'integer',array('limit'  =>  10,'default'=>0,'comment'=>'排序'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'创建时间'))
            ->addColumn('update_time', 'datetime',array('default'=>0,'comment'=>'更新时间'))
            ->create();
    }
}
