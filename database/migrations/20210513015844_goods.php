<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Goods extends Migrator
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
        $table  =  $this->table('goods',array('engine'=>'MyISAM'));
        $table->addColumn('name', 'string',array('limit'  =>  200,'default'=>'','comment'=>'标题'))
            ->addColumn('goods_types_id', 'integer',array('limit'  =>  10,'default'=>0,'comment'=>'商品分类id'))
            ->addColumn('sort', 'integer',array('limit'  =>  10,'default'=>0,'comment'=>'排序'))
            ->addColumn('start', 'integer',array('limit'  =>  5,'default'=>1,'comment'=>'状态'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'创建时间'))
            ->addColumn('update_time', 'datetime',array('default'=>0,'comment'=>'更新时间'))
            ->create();
    }
}
