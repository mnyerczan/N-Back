<?php

class logUotController
{
    function __construct()
    {
        $this->logOut();
    }

    function logOut()
    {
        session_start( [] );

        session_destroy();

        header('Location: '.APPROOT);
    }
}