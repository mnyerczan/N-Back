<?php

use Login\UserEntity;



class userController extends MainController
{

    function __construct($matches)
    {        
        parent::__construct($matches);

        $this->setDatas();

        $this->SearchAction();
    }


    protected function Action()
    {                       
        $this->Response( $this->datas, [
            'view'      => 'profile', 
            'module'    => 'User',
            "title"     => 'User',            
            ]  
        );
    }
}