<?php

namespace App\Controller\Errors;

use App\Core\BaseController;
use App\Model\ViewParameters;
use Exception;

class ExceptionErrorController extends BaseController
{
   private bool $isRendered = false;

    public function exception($exception)
    {        
        $traces = explode("#",$exception->getTraceAsString());

        $this->put("message", $exception->getMessage());
        $this->put("exception", get_class($exception));
        $this->put("file", $exception->getFile());
        $this->put("line", $exception->getLine());
        $this->put("previous", $exception->getPrevious());        
        $this->put("traces", $this->trace($exception->getTrace())); 

        $this->render();        
    }   

    public function userError($error)
    {         
       
        $errorType = "";        

        switch ($error[0]) {
            case "2": $errorType = "E_WARNING "; break;            
            case "8": $errorType = "E_NOTICE "; break;                                                
            case "256": $errorType = "E_USER_ERROR "; break;
            case "512": $errorType = "E_USER_WARNING "; break;
            case "1024": $errorType = "E_USER_NOTICE "; break;
            case "2048": $errorType = "E_STRICT "; break;
            case "4096": $errorType = "E_RECOVERABLE_ERROR"; break;
            case "8192": $errorType = "E_DEPRECATED "; break;
            case "16348": $errorType = "E_USER_DEPRECATED"; break;
            case "32767": $errorType = "E_ALL "; break;
        }
                
        $this->put("message", $error[1]);
        $this->put("exception", $errorType);
        $this->put("file", $error[2]);
        $this->put("line", $error[3]);
        $this->put("previous", null);
        $this->put("traces", $this->trace(debug_backtrace()));

        $this->render();
    }

    public function parseError()
    {
        
        $error = error_get_last();
        if($error == null)
            return;
                
 
        $errorType = "";

        switch ($error["type"]) {
            case "1": $errorType = "E_ERROR "; break;
            case "4": $errorType = "E_PARSE "; break;
            case "16": $errorType = "E_CORE_ERROR "; break;
            case "32": $errorType = "E_CORE_WARNING "; break;
            case "64": $errorType = "E_COMPILE_ERROR "; break;
            case "128": $errorType = "E_COMPILE_WARNING"; break;
        }

        $stackTrace = explode("#",$error["message"]);

        $this->put("message", explode(" in ",$stackTrace[0])[0]);
        $this->put("exception", $errorType);
        $this->put("file", $error["file"]);
        $this->put("line", $error["line"]);
        $this->put("previous", null);

        
        for ($i=1; $i < count($stackTrace); $i++) {
            $tmpTrace = explode(" ",$stackTrace[$i]);
            $stack[] = $tmpTrace[1]." ".$tmpTrace[2];
        }

        $this->put("traces", array_slice($stack, 1)); 

        $this->render();
    }

    private function trace($traceTmp)
    {   
        $trace = [];     
        foreach ($traceTmp as $value) {
            if (isset($value["file"]))
                $file = $value["file"]."(".$value["line"].")";
            else
                $file = "";            
            if (isset($value["class"]))
                $function = $value["class"].$value["type"].$value["function"]."()";
            else
                $function = $value["function"];
            $trace[] = $file." ".$function;
        }
        return $trace;
    }

    private function render()
    {
        if (!$this->isRendered)
        {
            $this->isRendered = true;   
            $this->Response(            
                new ViewParameters("", "text/html", "_exception", "errors", "Exception occurs")
            );    
        }
    }
}