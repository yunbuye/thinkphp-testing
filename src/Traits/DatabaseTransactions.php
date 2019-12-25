<?php

namespace Xwpd\ThinkTesting\Traits;

use think\Db;
use think\db\Connection;

trait DatabaseTransactions
{
    /**
     * 启动事务
     * @throws \think\Exception
     * @throws \Exception
     */
    public function beginDatabaseTransaction()
    {
        foreach ($this->connectionsToTransact() as $name) {
            $connection = Db::connect($name);
            /**
             * @var $connection Connection
             */
            $connection->startTrans();
        }
    }

    /**
     * 回滚事务
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function rollBackDatabase()
    {
        foreach ($this->connectionsToTransact() as $name) {
            $connection = Db::connect($name);
            /**
             * @var $connection Connection
             */
            $connection->rollback();
        }
    }

    /**
     * 注意：null 值，代表默认数据库。
     * @var array
     */
    protected $connectionsToTransact = [null];

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return $this->connectionsToTransact;
    }
}
