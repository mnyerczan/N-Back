<?php

/**
 * Ez az osztály beállítja a válasz fejlécet (status line) és
 * dönti el a válasz típusát.
 * 
 * 
 */

namespace App\Core;


use App\Core\ViewRenderer;
use App\Model\ViewParameters;
use App\Model\ModelAndView;


final class ResponseFactory
{
    private ViewRenderer $viewRenderer;


    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }


    public function createResponse(array $models, ViewParameters $viewParameters)
    {

        // Ha átirányítást kért a hívó.
        if (preg_match("`^redirect:`", $viewParameters->view))
        {
          
            return new Response(
                '', 
                ["location" => HTTP_PROTOCOL.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].substr($viewParameters->view, 9)], 
                302, 
                'Found'
            );
        }
        else
        {
            $modelAndView = new ModelAndView($viewParameters, $models);

            if (strpos($viewParameters->mime, "json"))
            {
                return new Response(
                    $this->viewRenderer->render($modelAndView), 
                    ["content-type" => "application/json"],
                    200, 
                    "Ok"
                );
            }

            elseif (preg_match("`^_404$`", $viewParameters->view))
            {               
                return new Response(
                    $this->viewRenderer->render($modelAndView), 
                    [],
                    404, 
                    'Page Not Found'
                );
            }

            else
            {                        
                return new Response($this->viewRenderer->render($modelAndView), [], 200, "Ok");
            }
        }            
    }
}