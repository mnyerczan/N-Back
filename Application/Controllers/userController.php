<?php

use Login\UserEntity;
use Model\Image\ImageConverter;


class userController extends MainController
{

    function __construct()
    {        
        parent::__construct();

        $this->setDatas();

        $this->Action();
    }


    private function Action()
    {           
        $this->Response( $this->datas, [
            'view'      => 'profile', 
            'module'    => 'User',
            "title"     => 'User'
            ]  
        );
    }
}