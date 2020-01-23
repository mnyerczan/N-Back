<?php

use DB\EntityGateway;

require_once APPROOT.'Model/Home/homeViewModel.php';

class Home
{
    private 
            $database,
            $sql = 'SELECT content FROM documents WHERE title = "start_page" AND privilege = 3';

    function __construct()
    {
        $this->database = EntityGateway::getDB();        
        
    }

    function getContent()
    {                            
        return new HomeViewModel( $this->database->Select( $this->sql )[0]->content );
    }
}