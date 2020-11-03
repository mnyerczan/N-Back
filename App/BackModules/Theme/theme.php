<?php

namespace Theme;

use DB\DBInterface;

class Theme
{
    private $dbObject,
            $newTheme;
    
    function __construct( DBInterface $dbObject, $newTheme )
    {
        $this->dbObject = $dbObject;  
        $this->newTheme = $newTheme;
    }
    
    function UpdateTheme( $id )
    {
        $sql = 'UPDATE users SET theme = :theme WHERE id = :id';
        $this->dbObject->Execute($sql, [ ':theme' => $this->newTheme, ':id' => $id])
    }
}