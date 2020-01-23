<?php
namespace DB;

use DB\MySql;

require_once 'MySql.php';


abstract class EntityGateway
{
    private static $db = 'DB\MySql';

    /**
     * Database abstraction level
     * 
     * @return object of a specified Database class
     */
    public static function getDB()
    {
        return self::$db::getInstance();
    }
}
