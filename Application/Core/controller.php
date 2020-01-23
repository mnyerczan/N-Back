<?php

class Controller
{    
    protected function View ( $view, array $datas = [] )
    {             
        extract( $datas );   

        require_once APPROOT.'Templates/Main/_layout.php';        
    }
}