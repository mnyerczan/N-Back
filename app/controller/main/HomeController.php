<?php

namespace App\Controller\Main;

use App\Model\ViewParameters;
use App\Model\Home;


class HomeController extends MainController
{

    function __construct($matches)
    {                    
        $this->setDatas();                    
        $this->put('home' , (new Home())->getContent());
    }
  
  
    function index()
    {
        $this->Response(          
            new ViewParameters('home', "text/html", "", "home", "Welcome!")
        );
    }
                 
}