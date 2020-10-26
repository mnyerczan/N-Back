<?php

/**
 * Model - view adatátadó osztály.
 * 
 * 
 */
namespace App\Model;



final class ModelAndView
{
    private $model,
            $viewParameters;

    public function __construct(ViewParameters $viewParameters, array $model = [])
    {
        $this->viewParameters   = $viewParameters;
        $this->model            = $model;
    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'model':   return $this->model; 
            case 'view' :   return $this->viewParameters; 
        }
    }
}