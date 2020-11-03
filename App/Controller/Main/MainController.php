<?php


namespace App\Controller\Main;


use App\Model\Sessions;
use App\Model\Seria;
use App\Model\Header;
use App\Model\Navbar;
use App\Model\Indicator;
use App\Core\BaseController;


class MainController extends BaseController
{    
            
    protected function SetDatas()
    {        
        $this->datas = [ 
            'seria' => (new Seria( $this->user->id ))->seria, 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'header' => (new Header( $this->user ))->getDatas()
        ];       
         
    }

}