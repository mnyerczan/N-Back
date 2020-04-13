<?php


class NotFoundController extends MainController
{
    function __construct()
    {              
        $this->Action();
    }


    function Action()
    {
        $this->Response([], [
            "view"      => "_404", 
            "module"    => "Errors",
            "title"     => "Page Not Found"
            ]
        );
    }

}