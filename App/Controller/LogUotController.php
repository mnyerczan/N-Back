<?php

namespace App\Controller;

use App\Core\MainController;
use App\Model\ViewParameters;


class LogUotController extends MainController
{ 

    function index()
    {        
        session_destroy();

        $this->Response([], new ViewParameters( 'redirect:'.APPROOT.'/'));
    }
}