<?php

namespace App\Model;

use App\Model\UserEntity;
use App\Model\Sessions;

use App\Services\User;

class Navbar
{
    private $user,
            $sessions,
            $menus;

    private $datas = [];            

    

    function __construct()    
    {
        $this->datas['childMenus'] = [];        
                
        $this->sessions = new Sessions(User::$id); 
        $this->menus    = new Menus(User::$privilege);

        $this->datas['menus']       = $this->menus->menus;
        $this->datas['childMenus']  = $this->menus->childMenus; 
        $this->datas['sessions']    = $this->sessions->sessions;
        $this->datas['times']       = $this->sessions->times;
        $this->datas['logoImg']     = 'images/brain_logo.png';
        
        
        
        for ($i=0; $i < count($this->datas['sessions']); $i++) 
        {  
            $this->datas['sessions'][$i]->endAt = substr($this->datas['sessions'][$i]->timestamp, 11, 5);
        }


    }



    function getDatas()
    {         
        return (object)$this->datas;
    }


    
}