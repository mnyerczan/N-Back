<?php


namespace App\Controller\Main;


use App\Model\Seria;
use App\Model\Header;
use App\Model\Navbar;
use App\Model\Indicator;
use App\Core\BaseController;


class MainController extends BaseController
{    
            
    protected function setDatas()
    {        
        $this->datas = [ 
            "user" => "App\Model\User",
            'seria' => (new Seria())->seria,
            'navbar'=> ( new Navbar() )->getDatas(),
            'header' => (new Header())->getDatas()
        ];       
         
    }

}