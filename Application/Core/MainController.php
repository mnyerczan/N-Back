<?php

use DB\EntityGateway;
use Login\UserEntity;
use Model\Image\ImageConverter;
use Model\Sessions;




class MainController
{    
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected   $user,
                $datas;              

    function __construct()
    {       
        $this->user = UserEntity::GetInstance();        
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
            'seria' => new Seria( $this->user->id ), 
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