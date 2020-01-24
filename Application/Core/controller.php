<?php

class Controller
{    
    protected function View ( $view, array $datas = [] )
    {             
        extract( $datas );   

        $module = str_replace('Controller', '', get_class($this));
        $module = strtoupper(substr($module, 0, 1)).substr($module,1);

        require_once APPROOT."Templates/{$module}/_layout.php";     
    }
}