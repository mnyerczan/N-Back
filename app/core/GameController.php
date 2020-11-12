<?php

namespace App\Core;

use App\Model\Seria;
use App\Model\Header;
use App\Services\DB;
use App\Services\User;

class GameController extends BaseController
{    
    protected function __construct()        
    {
        // DB::setup();
        // User::setup();
    }

    protected function setDatas()
    {        
        
        $this->put("seria", (new Seria())->seria);
        $this->put("user", "App\Services\User");
        $this->put("header",(new Header())->getDatas());
           
    }

}