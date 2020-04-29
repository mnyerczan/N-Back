<?php

use Login\UserEntity;



class userController extends MainController
{

    function __construct($matches)
    {        
        parent::__construct();

        $this->setDatas();

        if (array_key_exists('action',$matches))
        {
            $action = $matches['action'].'Action';
            $this->$action();
        }            
        else
            $this->Action();
    }


    private function Action()
    {      
        
        $this->Response( $this->datas, [
            'view'      => 'profile', 
            'module'    => 'User',
            "title"     => 'User',            
            ]  
        );
    }

    private function updateAction()
    {                       
        var_dump($_POST);
    }

}