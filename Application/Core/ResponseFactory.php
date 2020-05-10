<?php


final class ResponseFactory
{
    private $viewRenderer;


    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }


    public function createResponse($controllerResult)
    {         

        if (!is_array($controllerResult)) return false;
        
        if (preg_match("`^redirect:`", $controllerResult[0]['view']))
        {
            return new Response(
                '', 
                ["location" => 'http://localhost'.substr($controllerResult[0]['view'], 9)], 
                302, 
                'Found'
            );
        }
        elseif (preg_match("`^_404$`", $controllerResult[0]['view']))
        {
            $modelAndView = new ModelAndView($controllerResult[0], $controllerResult[1]);
            
            return new Response(
                $this->viewRenderer->render($modelAndView), 
                [],
                404, 
                'Page Not Found'
            );
        }
        else
        {                        
            $modelAndView = new ModelAndView($controllerResult[0], $controllerResult[1]);

            return new Response($this->viewRenderer->render($modelAndView), [], 200, "Ok");
        }
    }
}