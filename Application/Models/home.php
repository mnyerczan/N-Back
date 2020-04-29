<?php

use DB\DB;


class Home
{
    private 
            $db;            

    function __construct()
    {
        $this->db = DB::GetInstance();        
        
    }

    function getContent()
    {   
        $sql        = 'CALL `GetHomeContent`(:inPrivilege)';
        $params     = [':inPrivilege' => 3];     

        $content    = $this->db->Select($sql, $params);


        return new HomeViewModel($content[0] ?? '');
    }
}