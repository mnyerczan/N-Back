<?php


namespace App\Classes;


class ValidateUser extends Validator
{
    private $isAdmin;



    public function __construct( $userName = null, $isAdmin = null)
    {
        $this->value = $userName;
        $this->isAdmin = $isAdmin;
        
        parent::__construct();
    }



    function getUser()
    {
        return $this->value;
    }




    function validate() 
    {

        // Csak a magyar abc betüi, az aláhúzás és a . karakterek jelenhetnek meg a 
        // felhasználónévben!
        if (!preg_match('/^[a-zA-Z0-9 _áéíóöőúüűÁÉÍÓÖŐÚÜŰ\.,]+$/',$this->value ))
        {                                    
            $this->setError('Username contains invalid characters!');            
            return false;
        }

        // Nem lehet hosszabb 255 karakternél!
        if (strlen($this->value) > 255)
        {
            $this->setError('Username is too long!');
        }                       

        // Nem lehet admin a felhasználónév,
        if (!$this->isAdmin && preg_match("/^admin$/i",$this->value))
        {            
            $this->setError('Username cannot be Admin');
            return false;
        }      

        // Nem lehet 6 karakternél rövidebb a felhasználónév.
        if ( !$this->isAdmin && strlen($this->value) < 6 ) 
        {
            $this->setError('Username is too short');
            return false;
        }
  
    }


}