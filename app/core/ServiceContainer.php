<?php

namespace App\Core;


use InvalidArgumentException;



/**
 * Servicecontainer for service classes
 */
class ServiceContainer
{
    private static array $definitions = [];

    public static function load()
    {
        self::$definitions = require  "NBack/services/services.php";
    }

    public static function get($service, ?array $params = null)
    {
        if (self::$definitions == [])
            self::load();

        if (array_key_exists($service, self::$definitions)){
            if (is_callable(self::$definitions[$service])) {
                $factory = self::$definitions[$service];
                return self::$definitions[$service] = $factory(self::class, $params);                
            }
            else {
                return self::$definitions[$service];
            }
        }            
        throw new InvalidArgumentException("The named service \"{$service}\" do not exists!");
    }

    public static function add($serviceClassName, $path)
    {
        self::$definitions[$serviceClassName] = $path;
    }    
}