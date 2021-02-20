<?php


namespace App\Controller\Main;


use App\Model\Seria;
use App\Model\Header;
use App\Model\Navbar;
use App\Core\BaseController;


class MainController extends BaseController
{    
            
    protected function setDatas()
    {        
        $this->put("user", "App\Services\User"); 
        $this->put('seria', (new Seria())->seria);
        $this->put('navbar', ( new Navbar() )->getDatas());
        $this->put('header', (new Header())->getDatas());                        
         
    }

}