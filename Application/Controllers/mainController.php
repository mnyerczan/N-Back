<?php


require_once APPLICATION.'Model/home.php';


require_once APPLICATION.'Core/controller.php';

class MainController extends Controller
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
        $this->View( $this->datas, ['view' => 'main', 'module' => 'Main'] );
    }
                 
}