<?php

class ViewParameters
{
    private string  $view       = "homeView",
                    $mime       = "text/html", 
                    $layout     = "Main",
                    $title      = "Main",
                    $errorMsg   = "&#127540;",
                    $module     = "",
                    $item       = "";                


    public function __construct(
        $view       = "",
        $mime       = "",
        $layout     = "",        
        $module     = "",
        $title      = "",
        $errorMsg   = "",
        $item       = ""
    )
    {
        if ($mime)
            $this->mime     = $mime ;

        if ($layout)
            $this->layout   = $layout ;

        if ($view)
            $this->view     = $view ;

        if ($module)            
            $this->module   = $module ;

        if ($title) 
            $this->title    = $title ;
      
        $this->errorMsg = $errorMsg ;
        
        $this->item     = $item ;
    }


    public function __get($name)
    {
        switch ($name)
        {
            case 'view'     : return $this->view;       break;
            case 'layout'   : return $this->layout;     break;
            case 'mime'     : return $this->mime;       break;
            case 'module'   : return $this->module;     break;
            case 'title'    : return $this->title;      break;
            case 'errorMsg' : return $this->errorMsg;   break;            
            case 'item'     : return $this->item;       break;
            case 'personal' : return $this->personal;   break;            
        }
    }

}