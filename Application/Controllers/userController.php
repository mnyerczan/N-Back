<?php

use Login\UserEntity;
use Model\Image\ImageConverter;

require_once APPLICATION.'Core/MainController.php';

require_once APPLICATION.'Models/Image/ImageConverter.php';


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
        $this->View( $this->datas, ['view' => 'profile', 'module' => 'User' ]  );
    }
}