<?php

namespace App\Controller\Errors;

use App\Controller\Main\MainController;
use App\Model\ViewParameters;


class NotFoundController extends MainController
{
    function __construct()
    {     
    

        $this->setDatas();
    }


    function Action()
    {
        $this->Response(
            new ViewParameters(
                "_404", 
                "text/html", 
                "", 
                "errors", 
                "Page Not Found")
        );
    }

}