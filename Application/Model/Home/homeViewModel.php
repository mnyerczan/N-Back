<?php

class HomeViewModel
{
    private $content;

    function __construct( $content )
    {        
        $this->content = $content;
    }

    function __get($name)
    {
        switch ( $name )
        {
            case 'content': return $this->content; break; 
        }
    }
}