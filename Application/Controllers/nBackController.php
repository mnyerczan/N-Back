<?php

class nBackController extends GameController
{
    function __construct()
    {
        parent::__construct();
        $this->SetDatas();
      
    }

    public function index()
    {          
        $this->Response( 
            $this->datas, [
            'view'      => 'nback', 
            'layout'    => 'Nback',
            'module'    => 'Nback',
            "title"     => 'Nback',            
            ]  
        );
    }

}