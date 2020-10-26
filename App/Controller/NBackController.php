<?php

namespace App\Controller;


use App\Core\GameController;
use App\Model\ViewParameters;


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
            $this->datas, new ViewParameters(
                'nback',
                'text/html',
                "Nback",
                "Nback",
                "Nback") 
        );
    }

}