<?php


class LogUotController extends MainController
{ 

    function index()
    {        
        session_destroy();

        $this->Response([], ['view' => 'redirect:'.APPROOT.'/']);
    }
}