<?php

namespace App\Controller\Errors;

use App\Core\BaseController;
use App\Model\ViewParameters;

class DatabaseErrorController extends BaseController
{
    public function index()
    {
        $this->Response(
            ["message" => "Sorry, site has gone away... :("], 
            new ViewParameters("", "text/html", "_generalError", "Errors", "Database error")
        );
    
    }   
}