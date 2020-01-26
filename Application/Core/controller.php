<?php

class Controller
{    
    protected function View (array $datas = [], array $view = [])
    {             
        extract( $datas );   
        extract( $view );   
           

/*         $module = str_replace('Controller', '', get_class($this));
        $module = strtoupper(substr($module, 0, 1)).substr($module,1); */

        require_once APPLICATION."Templates/_layout.php";     
    }
}