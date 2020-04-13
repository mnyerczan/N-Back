<?php

use DB\EntityGateway;

require_once APPLICATION.'Models/Home/homeViewModel.php';

class Home
{
    private 
            $database;            

    function __construct()
    {
        $this->database = EntityGateway::GetInstance();        
        
    }

    function getContent()
    {                            
        return new HomeViewModel( $this->database->getHomeContent());
    }
}