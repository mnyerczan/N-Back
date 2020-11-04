<?php

namespace App\Core;

use App\Model\User;
use App\DB\DB;
use App\Model\ViewParameters;

class BaseController
{
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected array $datas;
       

    function __construct()
    {
        DB::setup();
        User::setup();                
    }



    /**
     * Az Respons-nak átadott paraméterek a view-k számára elérhetőek.
     * 
     * 
     * @param array $models Get datas for render views
     * @param array $viewAndModel Get name of view and module
     */
    protected function Response(array $models = [], ViewParameters $viewParameters): void 
    {
        $responseFactory = new ResponseFactory(new ViewRenderer);

        $response = $responseFactory->createResponse($models, $viewParameters );

        (new ResponseEmitter)->emit($response);

        exit;
    }   
    

}