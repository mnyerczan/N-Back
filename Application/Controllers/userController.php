<?php

use Login\UserEntity;
use Model\Image\ImageConverter;

require_once APPLICATION.'Core/controller.php';
require_once APPLICATION.'Model/Image/ImageConverter.php';


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