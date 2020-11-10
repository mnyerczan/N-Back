<?php

namespace App\Controller\Errors;

use App\Controller\Main\MainController;
use App\Model\ViewParameters;


class NotFoundController extends MainController
{
    function __construct()
    {     

        parent::__construct();

        $this->setDatas();
    }


    function Action()
    {
        $this->Response(
            $this->datas, 
            new ViewParameters(
                "_404", 
                "text/html", 
                "", 
                "Errors", 
                "Page Not Found")
        );
    }

}