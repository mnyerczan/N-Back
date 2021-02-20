<?php

namespace App\Core;

use App\Model\ViewParameters;
use InvalidArgumentException;


class BaseController
{
    // osztály invariáns - az osztály lehetéges állapotait írja le
    protected array $models= [];
       
    protected function put(string $name, $value)
    {
        $this->models[$name] = $value;
    }

    protected function get($name) 
    {
        if (array_key_exists($name, $this->models))
            return $this->models[$name];
        throw new InvalidArgumentException("The needed member variable \"{$name}\" does not exists!");
    }
    
    /**
     * Az Respons-nak átadott paraméterek a view-k számára elérhetőek.
     *  
     * @param array $viewAndModel Get name of view and module
     * @return void
     */
    protected function Response(ViewParameters $viewParameters): void 
    {
        $responseFactory = new ResponseFactory(new ViewRenderer);

        $response = $responseFactory->createResponse($this->models, $viewParameters );

        (new ResponseEmitter)->emit($response);

        exit;
    }   
    

}