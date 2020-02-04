<?php
namespace DB;

interface DBInterface
{   
     
    /**
     * Inicialize static singleton object.
     * @access public
     * 
     * @return self object 
     * 
     */
    static function GetInstance(): object;  
    function getHomeContent(): string;
    function getSeria(): array;

    //function Select( string $script, array $params = [] ): array;
    //function Execute( string $script, array $params = [] ); 
    
}