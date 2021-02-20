<?php


namespace App\Classes;


class ValidateSex extends Validator
{
    
    public function __construct( $sex = null) 
    {
        $this->value = $sex;        

        parent::__construct();
    }

    public function getSex()
    {
        return $this->value;
    }

    /**
     * Ha nincs a kapott érték a ['male', 'female'] tömbben, akkor nem valid.
     * 
     */
    public function validate() 
    {
       
        if (!in_array($this->value, ['male','female']) ) 
        {
            $this->setError('Sour sex must be felame or male!');            
        }     
    }
}