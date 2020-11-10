<?php

namespace App\Core;

class ErrorController
{
    protected function View (array $datas = [], $view = NULL)
    {             
        extract( $datas );   
           

        $module = str_replace('Controller', '', get_class($this));
        $module = strtoupper(substr($module, 0, 1)).substr($module,1);

        require_once APPLICATION."Templates/Errors/{$module}/_layout.php";     
    }
}