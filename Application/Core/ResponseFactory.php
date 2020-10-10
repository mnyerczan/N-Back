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
            //var_dump($_SERVER); die;
            return new Response(
                '', 
                ["location" => HTTP_PROTOCOL.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].substr($controllerResult[0]['view'], 9)], 
                302, 
                'Found'
            );
        }
        else{
            $modelAndView = new ModelAndView($controllerResult[0], $controllerResult[1]);

            if ($controllerResult[0]['mime'] === ' application/json')
            {
                return new Response(
                    $this->viewRenderer->render($modelAndView), 
                    ['content-type' => ' application/json'],
                    200, 
                    'Ok'
                );
            }
            elseif (preg_match("`^_404$`", $controllerResult[0]['view']))
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