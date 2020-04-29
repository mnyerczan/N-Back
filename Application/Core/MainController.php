<?php

use Login\UserEntity;
use Model\Sessions;




class MainController
{    
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected   $user,
                $datas;
    
    private $action;

    function __construct($matches = null)
    {       
        $this->user = UserEntity::GetInstance(); 

        if (array_key_exists('action', $matches))      
            $this->action = $matches['action'];
    }


    protected function SearchAction()
    {
        foreach (get_class_methods($this) as $function) 
        {
            if ($function == $this->action)
            {
                $function();
                return true;
            }                
        }        
        return $this->Action();
    }
  

    /**
     * Előfeltétel - bemeneti paraméterek
     * 
     * @param array $datas Get datas for render views
     * @param array $viewModule Get name of view and module
     */
    protected function Response(array $models = [], array $viewAndModule = []): void 
    {             
        $responseFactory = new ResponseFactory(new ViewRenderer);
        $response = $responseFactory->createResponse([$viewAndModule, $models]);

        (new ResponseEmitter)->emit($response);        
    }   
    
    
    
    
    protected function SetDatas()
    {        

        $this->datas = [ 
            'seria' => (new Seria( $this->user->id ))->seria, 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (
                Indicator::getInstance(
                    new Sessions( $this->user->id, 1 ),
                    $this->user->gameMode 
                )
            )->getDatas(),
            'header' => (new Header( $this->user ))->datas
        ];       
           
    }

}