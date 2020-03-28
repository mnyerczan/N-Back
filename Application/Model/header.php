<?php

use Login\UserEntity;

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

        $datas['userIconPath']      = RELPATH.APPLICATION.'img/'.$this->user->fileName;
        $datas['logoutIconPath']    = RELPATH.APPLICATION."img/logout.png";
        $datas['seriaIconPath']     = RELPATH.APPLICATION."img/fat_flame_red.png";
        $datas['javaScript']        = RELPATH.APPLICATION."Templates/Header/header.js";
        


        return $datas;
    }
}