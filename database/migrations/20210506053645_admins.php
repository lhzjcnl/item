<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Admins extends Migrator
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
        $table  =  $this->table('admins',array('engine'=>'MyISAM'));
        $table->addColumn('name', 'string',array('limit'  =>  15,'default'=>'','comment'=>'用户名'))
            ->addColumn('password', 'string',array('limit'  =>  100,'default'=>md5('admins'),'comment'=>'用户密码'))
            ->addColumn('token', 'string',array('limit'  =>  100,'default'=>'','comment'=>'token'))
            ->addColumn('role', 'string',array('limit'  =>  10,'default'=>'普通用户','comment'=>'角色'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'创建时间'))
            ->addColumn('update_time', 'datetime',array('default'=>0,'comment'=>'更新时间'))
            ->addIndex(array('name'), array('unique'  =>  true))
            ->create();
    }
}
