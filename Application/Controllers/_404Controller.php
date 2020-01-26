<?php

use Login\UserEntity;

require_once APPLICATION.'Core/errorController.php';

class _404Controller extends ErrorController
{
    private $backFromCurrentPath;
    function __construct()
    {              
        $num = count(explode( '/' , URI ) ) - 2;

        
        for ( $i = 0; $i < $num; $i++ )
        {
            $this->backFromCurrentPath.= '../';
        }

        $this->Action();
    }


    function Action()
    {
        $this->View( [ 'path' => $this->backFromCurrentPath ] );
    }

}