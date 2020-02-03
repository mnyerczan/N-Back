<?php

use Login\UserEntity;

require_once APPLICATION.'Core/errorController.php';

class _404Controller extends ErrorController
{
    function __construct()
    {              
        $this->Action();
    }


    function Action()
    {
        $this->View();
    }

}