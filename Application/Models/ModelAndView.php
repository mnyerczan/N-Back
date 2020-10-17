<?php

/**
 * Model - view adatátadó osztály.
 * 
 * 
 */

final class ModelAndView
{
    private $model,
            $viewName;

    public function __construct(array $viewName, array $model = [])
    {
        $this->viewName = $viewName;
        $this->model = $model;
    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'model' :      return $this->model; 
            case 'viewName' :   return $this->viewName; 
        }
    }
}