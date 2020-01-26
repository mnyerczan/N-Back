<?php

use Login\UserEntity;
use Model\Sessions;

require_once APPLICATION.'Model/sessions.php';

/**
 * This is a singleton!
 */
class Indicator
{
    public static
            $session,
            $INSTANCE,
            $datas;

    public static function GetInstance( Sessions $sessions ): object
    {
        if ( self::$INSTANCE == NULL )
        {
            self::$INSTANCE = new self();  
            self::$session  = $sessions;
                        
            self::Load();                         
            self::setCoocies();
        }               
        return self::$INSTANCE;
    }

    public static function getDatas()
    {
        return self::$datas;
    }

    private static function setCoocies()
    {
        if( @!$_COOKIE['infoLabel']) 
        {
            setcookie('infoLabel', 'On');
            self::$datas->infoLabel = 'On';
        }  
        else 
            self::$datas->infoLabel = $_COOKIE['infoLabel'];
    }

    private static function Load(): void
    {        
        self::$datas                = self::$session->sessions[0];                
        self::$datas->gameMode      = self::$session->gameMode === 'Position' ? 'Position '.self::$session->level.' - back' : 'Manual';     

        $sessionLength              = (int)self::$datas->sessionLength / 1000;     
        self::$datas->sessionLength = floor( $sessionLength / 60).':'.(strlen($sessionLength % 60) == 1 ?  '0'.round($sessionLength  % 60).' m' : $sessionLength % 60 );

    }
}