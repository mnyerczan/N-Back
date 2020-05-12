<?php


class logUotController extends MainController
{
    function __construct()
    {
        $this->Action();
    }

    function Action()
    {        
        session_destroy();

        $this->Response([], ['view' => 'redirect:'.APPROOT.'/']);
    }
}