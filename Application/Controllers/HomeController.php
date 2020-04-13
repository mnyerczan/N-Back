<?php



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
        $this->Response( $this->datas, ['view' => 'home', 'module' => 'Home'] );
    }
                 
}