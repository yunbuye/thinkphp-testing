<?php

namespace Xwpd\ThinkTesting;

use think\db\Connection as BaseConnection;

class Connection extends BaseConnection
{
    public function refreshConnection()
    {
        foreach (self::$instance as $item) {
            /**
             * @var  \think\db\Connection $item
             */
            $item->close();
        }
        self::$instance = [];
    }

    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
     * @return string
     */
    protected function parseDsn($config)
    {
        // TODO: Implement parseDsn() method.
    }

    /**
     * 取得数据表的字段信息
     * @access public
     * @param string $tableName
     * @return array
     */
    public function getFields($tableName)
    {
        // TODO: Implement getFields() method.
    }

    /**
     * 取得数据库的表信息
     * @access public
     * @param string $dbName
     * @return array
     */
    public function getTables($dbName)
    {
        // TODO: Implement getTables() method.
    }

    /**
     * SQL性能分析
     * @access protected
     * @param string $sql
     * @return array
     */
    protected function getExplain($sql)
    {
        // TODO: Implement getExplain() method.
    }
}