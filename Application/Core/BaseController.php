<?php

use Login\UserEntity;

class BaseController
{
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected UserEntity $user;
    protected array $datas;
       

    function __construct()
    {
        $this->user = UserEntity::GetInstance();    
    }



    /**
     * Az Respons-nak átadott paraméterek a view-k számára elérhetőek.
     * 
     * 
     * @param array $models Get datas for render views
     * @param array $viewAndModel Get name of view and module
     */
    protected function Response(array $models = [], array $viewAndModel = []): void 
    {   
    
        
        $responseFactory = new ResponseFactory(new ViewRenderer);
        
        $response = $responseFactory->createResponse([$viewAndModel, $models]);
        
        (new ResponseEmitter)->emit($response);    
    }   
    

}