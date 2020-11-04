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

        //$datas['userIconPath']      = BACKSTEP.APPLICATION.'Images/'.$this->user->fileName;
        $datas['logoutIconPath']    = BACKSTEP.APPLICATION."Images/logout.png";
        $datas['seriaIconPath']     = BACKSTEP.APPLICATION."Images/fat_flame_red.png";
        $datas['javaScript']        = BACKSTEP."Public/js/header.js";
        


        return $datas;
    }
}