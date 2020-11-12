<?php

namespace App\Controller\Errors;

use App\Core\BaseController;
use App\Model\ViewParameters;

class ExceptionErrorController extends BaseController
{
    public function index($exception)
    {
        $this->put("message", $exception->getMessage());
        $this->put("exception", get_class($exception));
        $this->put("file", $exception->getFile());
        $this->put("line", $exception->getLine());
        $this->put("previous", $exception->getPrevious());
        $this->put("traces", explode("#",$exception->getTraceAsString()));
        
        $this->Response(            
            new ViewParameters("", "text/html", "_exception", "Errors", "Exception occurs")
        );
    
    }   
}