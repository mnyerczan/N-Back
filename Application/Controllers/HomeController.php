<?php



class HomeController extends MainController
{


    function __construct($matches)
    {        
        parent::__construct();
            
        $this->SetDatas();
                    
        $this->datas['home'] = ( new Home() )->getContent();
        
    }
  
  
    function index()
    {
        $this->Response( $this->datas, new ViewParameters('home', "text/html", "Main","Home", "Welcome!"));
    }
                 
}