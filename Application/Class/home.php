<?php

use DB\EntityGateway;

require_once APPROOT.'Model/Home/homeViewModel.php';

class Home
{
    private 
            $database;            

    function __construct()
    {
        $this->database = EntityGateway::getDB();        
        
    }

    function getContent()
    {                            
        return new HomeViewModel( $this->database->getHomeContent());
    }
}