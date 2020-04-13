<?php


require_once APPLICATION.'Model/home.php';
require_once APPLICATION.'Core/MainController.php';

class HomeController extends MainController
{


    function __construct($matches)
    {
        parent::__construct();
               
        $this->SetDatas();
                    
        $this->datas['home'] = ( new Home() )->getContent();

        $this->Action();
    }
  
  
    function Action()
    {                            
        $this->View( $this->datas, ['view' => 'home', 'module' => 'Home'] );
    }
                 
}