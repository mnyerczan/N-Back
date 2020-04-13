<?php
use DB\EntityGateway;

require_once APPLICATION.'DB/EntityGateway.php';

class Menus
{
    private $menus,
            $childMenus,
            $userPrivilege;

    private $database;

    function __construct( $userPrivilege )
    {
        $this->database = EntityGateway::GetInstance(); 
        $this->userPrivilege = $userPrivilege;
        

        $this->LoadMenus();
        $this->LoadChildMenus();      
    }

    function __get($name)
    {
        switch ( $name )
        {
            case 'menus':       return $this->menus;        break;
            case 'childMenus':  return $this->childMenus;   break;
        }
    }
    

    function LoadChildMenus()
    {
        $childMenu = [];

        for( $i = 0; $i < count( $this->menus ); $i++ )
        {
            $childMenu = $this->database->getChildMenus( $this->menus[$i]->id, $this->userPrivilege );

            if( count( $childMenu ) > 0 )
            {
                $this->childMenus[ (string)$this->menus[$i]->id ] = $childMenu;
            }                
        } 
                
    }    


    function LoadMenus()
    {        
        $this->menus = $this->database->getMenus( $this->userPrivilege );

        for ( $i=0; $i < count( $this->menus ); $i++ ) 
        { 
            $this->menus[$i]->name = strlen(Include_special_characters($this->menus[$i]->name)) > 8 ? 
                substr(Include_special_characters( $this->menus[$i]->name ), 0, 8).'..' : Include_special_characters( $this->menus[$i]->name );
        }
    }
}