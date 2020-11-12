<?php

namespace App\Model;


use App\Classes\ImageConverter;
use App\Model\UserEntity;




class Header 
{

    private $user,
            $datas;

    function __construct()
    {        
        $this->datas = $this->setDatas();
    }


    public function getDatas()
    {
        return (object)$this->datas;
    }

    private function setDatas()
    {     
        $datas = [];

        //$datas['userIconPath']      = BACKSTEP.APPLICATION.'images/'.$this->user->fileName;
        $datas['logoutIconPath']    = BACKSTEP.APPLICATION."images/logout.png";
        $datas['seriaIconPath']     = BACKSTEP.APPLICATION."images/fat_flame_red.png";
        $datas['javaScript']        = BACKSTEP."public/js/header.js";
        


        return $datas;
    }
}