<?php

namespace App\Controller\Main;


use Login\UserEntity;
use App\Model\ViewParameters;



class AccountController extends MainController
{    

    function __construct()
    {        
        $this->setDatas();
    }


    public function index()
    {              
        $this->Response(new ViewParameters('account', 'text/html', '', 'User') );
    }    

}