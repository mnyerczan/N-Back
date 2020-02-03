<?php

class Controller
{    

    protected function View (array $datas = [], array $viewModule = [])
    {             
        extract( $datas );   
        extract( $viewModule ); 
        
        
        unset( $datas ); 
        unset( $viewModule );    
            

        require_once APPLICATION."Templates/_layout.php";     
    }    
}