<?php

namespace App\Core;

use App\Model\Seria;
use App\Model\Header;
use App\DB\DB;
use App\Model\User;

class GameController extends BaseController
{    
    protected function __construct()        
    {
        DB::setup();
        User::setup();
    }

    protected function setDatas()
    {        
        $this->datas = [ 
            'seria' => (new Seria())->seria, 
            'user'  => "App\Model\User",                 
            'header' => (new Header())->getDatas()
        ];       
           
    }

}