<?php

namespace App\Model;


use App\Services\DB;
use App\Db\Entities\MenuEntity;

class Menus
{
    private $menus,
            $childMenus,
            $userPrivilege;
    
    function __construct( $userPrivilege )
    {  
        $this->userPrivilege = $userPrivilege;
        

        $this->LoadMenus();
        $this->LoadChildMenus();      
    }

    function __get($name)
    {
        switch ( $name ){
            case 'menus':       return $this->menus;        break;
            case 'childMenus':  return $this->childMenus;   break;
        }
    }
    

    function LoadChildMenus()
    {                 
        $params = [
            "inPrivilege" =>  $this->userPrivilege
        ];

        for( $i = 0; $i < count( $this->menus ); $i++ )
            if ($this->menus[$i]->child) {
                # Megadjuk az aktuális parent menü azonostóját
                $params["inMenuId"] = $this->menus[$i]->id;

                $childMenuArray = DB::selectAll(
                    "CALL GetChildMenus(:inMenuId, :inPrivilege)", 
                    $params, 
                    MenuEntity::class
                );
    
                if(count($childMenuArray) > 0) {     
                    # Azonosító alapján elhelyezzük az osztály childMenu attribútumában
                    # a menü azonosító alá.
                    $this->childMenus[(string)$this->menus[$i]->id] = $childMenuArray; 
                }
            }                        
    }    


    function LoadMenus()
    {                   
        $this->menus = DB::selectAll(
            "CALL GetMenus(:inPrivilege)", 
            [ ':inPrivilege' => $this->userPrivilege ], 
            MenuEntity::class
        );
    }
}