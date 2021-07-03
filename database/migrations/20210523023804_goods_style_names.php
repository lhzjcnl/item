<?php

use think\migration\Migrator;
use think\migration\db\Column;

class GoodsStyleNames extends Migrator
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
        $table  =  $this->table('goods_style_names',array('engine'=>'MyISAM'));
        $table->addColumn('style_name', 'string',array('limit'  =>  10,'default'=>'','comment'=>'样式名'))
            ->addColumn('size_name', 'string',array('limit'  =>  10,'default'=>'','comment'=>'大小名'))
            ->addColumn('goods_id', 'integer',array('limit'  =>  10,'default'=>0,'comment'=>'商品id'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'创建时间'))
            ->addColumn('update_time', 'datetime',array('default'=>0,'comment'=>'更新时间'))
            ->addIndex(array('goods_id'), array('unique'  =>  true))
            ->create();
    }
}
