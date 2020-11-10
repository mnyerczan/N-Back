<?php

namespace App\Controller\Errors;

use App\Core\BaseController;
use App\Model\ViewParameters;

class ExceptionErrorController extends BaseController
{
    public function index($exception)
    {
// var_dump($exception->getTrace()); die;
        $this->Response(
            [
                "message" => $exception->getMessage(),
                "exception" => get_class($exception),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "previous" => $exception->getPrevious(),
                "traces" => explode("#",$exception->getTraceAsString())
            ], 
            new ViewParameters("", "text/html", "_exception", "Errors", "Exception occurs")
        );
    
    }   
}