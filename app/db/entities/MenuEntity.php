<?php

namespace App\DB\Entities;

use InvalidArgumentException;

class MenuEntity
{
    private int $id;
    private string $name;
    private ?int $parentId;
    private string $path;
    private ?string $ikon;
    private int $privilege;
    private int $chile;


    public function __get($name)
    {    
        if (in_array($name, get_class_vars(MenuEntity::class)))
            throw new InvalidArgumentException("The propertie does not exists! \"{$name}\"");
            
        return $this->$name;
    }

    public function __construct()
    {
        $this->normalize();
    }

    public function normalize()
    {
        $length = 20;
        // Kiegészítjük az image path-t
        if ($this->ikon)
            $this->ikon = BACKSTEP.APPLICATION."images/".$this->ikon; 
        // Lerövidítjük a nevet, ha túl hosszú
        // Ezt ki kellene emelni egy függvénybe
        if(strlen($this->name) > $length)
            $this->name = substr($this->name , 0, $length).'..' ;  

        $this->path = APPROOT.$this->path;
    }
}