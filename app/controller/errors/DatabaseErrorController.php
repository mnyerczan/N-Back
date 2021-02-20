<?php

namespace App\Controller\Errors;

use App\Core\BaseController;
use App\Model\ViewParameters;

class DatabaseErrorController extends BaseController
{
    public function index()
    {
        $this->put("message", "Sorry, site has gone away... :(");
        $this->Response(            
            new ViewParameters("", "text/html", "databaseError", "Errors", "Database error")
        );
    
    }   
}