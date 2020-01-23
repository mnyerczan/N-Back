<?php

namespace DB;

abstract class baseDbApi
{
    protected static $connect;    

    protected function __construct()
    {
        $this->id = rand();
    }

    

    public function __destruct()
    {
        self::$connect = NULL;
    }

    

    public function __get( $name )
    {
        switch( $name )
        {
            case "connect": return self::$connect; break;
            case "id": return $this->id; break;
        }
    }
}