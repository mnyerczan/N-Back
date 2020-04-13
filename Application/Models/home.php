<?php

use DB\EntityGateway;


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