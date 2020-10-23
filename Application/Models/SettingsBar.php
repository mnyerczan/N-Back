<?php

namespace App\Model;

use Exception;
use RuntimeException;
use UnexpectedValueException;

class SettingsBar
{
    private $submenus =  [ 
        "personalItem"  => [
            "status"    => "inactive", 
            "priority"  => 0, 
            "available" => 0
        ], 
        "nbackItem"     => [
            "status"    => "inactive", 
            "priority"  => 0, 
            "available" => 0
        ], 
    ];



    /**
     * Megkapja a hívó kontrollerhez tartozó almenű nevét és
     * aktív állapotba állítja.
     */
    public function __construct(string $callerSubMenu, int $userId = 0)
    {
        $counter = 0;

        foreach($this->submenus as $key => $menu)
        {
            if ($callerSubMenu == $key)
            {                
                $this->submenus[$key]["status"] = "active";
                $counter = 1;
            }

            if ($menu['priority'] <= $userId)            
                $this->submenus[$key]['available'] = 1;     
            
            
        } 

        if (!$counter)
            throw new Exception("Invalid constructor parameter");        
     
    }




    public function __get($name)
    {      
        switch($name)
        {
            case "personalItem" : return (object)$this->submenus["personalItem"]; break;
            case "nbackItem"    : return (object)$this->submenus["nbackItem"]   ; break;
            case "submenus"     : return $this->submenus                        ; break;
            
            default: 
                throw new UnexpectedValueException("The needed variable doesen't exists! \"".$name."\"");
        }
    }
}


