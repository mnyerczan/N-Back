<?php

use Login\UserEntity;
use Model\Image\ImageConverter;



class Header 
{

    private $user,
            $datas;

    function __construct( UserEntity $user )
    {
        $this->user = $user;

        $this->datas = $this->setDatas();
    }


    public function __get($name)
    {
        if( $name === 'datas' ) return (object)$this->datas;
    }

    private function setDatas()
    {     
        $datas = [];

        $datas['userName']          = $this->user->userName;
        $datas['theme']             = $this->user->theme;  
        $datas['userId']            = $this->user->id;
        $datas['privilege']         = $this->user->privilege;
        $datas['loginDatetime']     = $this->user->loginDatetime;
        
        // Ha nincs beloginolva felhasználó, az imgBin null értéket ad.
        $datas['imgBin']            = ImageConverter::BTB64($this->user->imgBin);

        //$datas['userIconPath']      = BACKSTEP.APPLICATION.'img/'.$this->user->fileName;
        $datas['logoutIconPath']    = BACKSTEP.APPLICATION."img/logout.png";
        $datas['seriaIconPath']     = BACKSTEP.APPLICATION."img/fat_flame_red.png";
        $datas['javaScript']        = BACKSTEP.APPLICATION."Templates/Header/header.js";
        


        return $datas;
    }
}