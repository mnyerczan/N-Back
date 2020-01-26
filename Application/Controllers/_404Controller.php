<?php

use Login\UserEntity;

require_once APPLICATION.'Core/errorController.php';

class _404Controller extends ErrorController
{
    private $backFromCurrentPath;
    function __construct()
    {              
        $num = count(explode( '/' , URI ) ) - 2;

        /**
         * A számlálás 1-től indul, mert az explode a /Thesis_v.2.0/error stringet 3 részre szeleteli.
         */
        for ( $i = 1; $i < $num; $i++ )
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