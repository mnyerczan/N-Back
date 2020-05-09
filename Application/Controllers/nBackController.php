<?php

class nBackController extends GameController
{
    function __construct()
    {
        parent::__construct();
        $this->SetDatas();

        $this->Action();
    }

    private function Action()
    {          
        $this->Response( 
            $this->datas, [
            'view'      => 'nback', 
            'module'    => 'Nback',
            "title"     => 'Nback',            
            ]  
        );
    }

}