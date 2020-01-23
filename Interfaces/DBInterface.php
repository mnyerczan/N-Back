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

    public function Select( string $script, array $datas = [] ): array;
    public function Execute( string $script, array $datas = [] ): bool;
    
}