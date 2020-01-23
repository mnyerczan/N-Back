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

    public function Select( string $script, array $params = [] ): array;
    public function Execute( string $script, array $params = [] ): bool;
    
}