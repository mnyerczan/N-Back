<?php
use DB\DB;


class Menus
{
    private $menus,
            $childMenus,
            $userPrivilege;

    private $db;

    function __construct( $userPrivilege )
    {
        $this->db = DB::GetInstance(); 
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
        $sql = 'CALL GetChildMenus(:inMenuId, :inPrivilege)';
        

        for( $i = 0; $i < count( $this->menus ); $i++ )
        {
            $params = [ 'inMenuId' => $this->menus[$i]->id, 'inPrivilege' =>  $this->userPrivilege];
            $childMenu = $this->db->Select($sql, $params);

            if( count( $childMenu ) > 0 )
            {
                $this->childMenus[ (string)$this->menus[$i]->id ] = $childMenu;
            }                
        } 
                
    }    


    function LoadMenus()
    {   
        $sql    = "CALL GetMenus(:inPrivilege)";
        $params = [ ':inPrivilege' => $this->userPrivilege ];     

        $this->menus = $this->db->Select($sql, $params);

        for ( $i=0; $i < count( $this->menus ); $i++ ) 
        { 
            $this->menus[$i]->name = strlen(Include_special_characters($this->menus[$i]->name)) > 8 ? 
                substr(Include_special_characters( $this->menus[$i]->name ), 0, 8).'..' : Include_special_characters( $this->menus[$i]->name );
        }
    }
}