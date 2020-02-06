<?php

use Login\UserEntity;

require_once APPLICATION.'Core/controller.php';


class userController extends Controller
{

    function __construct()
    {        
        parent::__construct();

        $this->setDatas();

        $this->Action();
    }


    private function Action()
    {

        $this->View( $this->datas, ['view' => 'profile', 'module' => 'User' ]  );
    }
}