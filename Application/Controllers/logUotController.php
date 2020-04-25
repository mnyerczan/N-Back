<?php


class logUotController extends MainController
{
    function __construct()
    {
        $this->logOut();
    }

    function logOut()
    {
        session_start( [] );

        session_destroy();

        $this->Response([], ['view' => 'redirect:'.APPROOT]);
    }
}