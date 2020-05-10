<?php

use Login\UserEntity;



class accountController extends MainController
{    

    function __construct($matches)
    {        
        parent::__construct();

        $this->setDatas();

        $this->Action();
    }


    private function Action()
    {              
        $this->Response( 
            $this->datas, [
            'view'      => 'account', 
            'module'    => 'User',
            "title"     => 'User',            
            ]  
        );
    }    

}