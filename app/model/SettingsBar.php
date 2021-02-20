<?php

namespace App\Model;

use Exception;
use UnexpectedValueException;
use App\Services\User;

class SettingsBar
{
    private $submenus =  [ 
        "personalItem"  => [
            // CSS-hez osztály!!
            "name"      => "personal",
            "status"    => "inactive", 
            "privilege"  => 1, 
            "available" => false,
            "url"       => "settings"
        ], 
        "nbackItem"     => [
            "name"      => "nback",
            // CSS-hez osztály!!
            "status"    => "inactive", 
            "privilege"  => 0, 
            "available" => false,
            "url"       => "settings/nback"
        ], 
    ];



    /**
     * Megkapja a hívó kontrollerhez tartozó almenű nevét és
     * aktív állapotba állítja.
     */
    public function __construct(string $callerSubMenu, int $userId = 1)
    {
        $counter = 0;
        foreach($this->submenus as $key => $menu){
            if ($callerSubMenu == $key){                
                $this->submenus[$key]["status"] = "active";
                $counter = 1;
            }

            if ($menu['privilege'] <= User::$privilege)            
                $this->submenus[$key]['available'] = true;
        } 

        if (!$counter)
            throw new Exception("Invalid constructor parameter");             
    }




    public function __get($name)
    {      
        switch($name) {
            case "personalItem" : return (object)$this->submenus["personalItem"]; break;
            case "nbackItem"    : return (object)$this->submenus["nbackItem"]   ; break;
            case "submenus"     : return $this->submenus                        ; break;
            
            default: 
                throw new UnexpectedValueException("The needed variable doesen't exists! \"".$name."\"");
        }
    }
}


