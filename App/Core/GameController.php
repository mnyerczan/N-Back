<?php

namespace App\Core;

use App\Model\Seria;
use App\Model\Header;

class GameController extends BaseController
{    
            
    protected function SetDatas()
    {        
        $this->datas = [ 
            'seria' => (new Seria( $this->user->id ))->seria, 
            'user'  => $this->user,                 
            'header' => (new Header( $this->user ))->getDatas()
        ];       
           
    }

}