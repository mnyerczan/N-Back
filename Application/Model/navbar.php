<?php

use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';
require_once APPLICATION.'Model/menus.php';

class Navbar
{
    private $user,
            $sessions,
            $menus;

    private $datas = [];            

    

    function __construct( UserEntity $user )    
    {
        $this->datas['childMenus'] = [];

        $this->user     = $user;
 
        $this->sessions = new Sessions( $this->user->id );
        $this->menus    = new Menus( $this->user->id );

        $this->datas['menus']       = $this->menus->menus;
        $this->datas['childMenus']  = $this->menus->childMenus; 
        $this->datas['sessions']    = $this->sessions->sessions;
        $this->datas['times']       = $this->sessions->times;
        
        
        for ($i=0; $i < count($this->datas['sessions']); $i++) 
        {  
            $this->datas['sessions'][$i]->endAt = substr($this->datas['sessions'][$i]->timestamp, 11, 5);
        }

    }



    function getDatas()
    {         
        return $this->datas;
    }


    private function Render()
    {

    }
    
}