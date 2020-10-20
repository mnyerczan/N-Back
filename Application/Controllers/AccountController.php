<?php

use Login\UserEntity;



class AccountController extends MainController
{    

    function __construct($matches)
    {        
        parent::__construct();

        $this->setDatas();

    }


    public function index()
    {              
        $this->Response( $this->datas, new ViewParameters('account', 'text/html', 'Main', 'User') );
    }    

}