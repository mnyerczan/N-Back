<?php


class NotFoundController extends MainController
{
    function __construct()
    {     

        parent::__construct();

        $this->setDatas();         
        $this->Action();
    }


    function Action()
    {
        $this->Response($this->datas, [
            "view"      => "_404", 
            'layout'    => 'Main',
            "module"    => "Errors",
            "title"     => "Page Not Found"
            ]
        );
    }

}