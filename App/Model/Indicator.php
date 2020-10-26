<?php

namespace App\Model;

use App\Model\Sessions;


/**
 * This is a singleton!
 */
class Indicator
{
    public static
            $session,
            $INSTANCE,
            $gameMode,
            $datas;

    public static function getDatas()
    {
        return self::$datas;
    }

    private static function setCoocies()
    {
        if( @!$_COOKIE['infoLabel']) 
        {           
            setcookie('infoLabel', 'On', time() + 60 * 60 *24 * 365, APPROOT.'/');
            self::$datas->infoLabel = 'On';
        }  
        else 
            self::$datas->infoLabel = $_COOKIE['infoLabel'];
    }

    private static function Load(): void
    {        
        self::$datas                = self::$session->sessions[0];
        
        self::$gameMode             = self::$datas->gameMode === 'Position' ? 'Position '.self::$session->level.' - back' : 'Manual';     

        $sessionLength              = (int)self::$datas->sessionLength / 1000;     
        self::$datas->sessionLength = floor( $sessionLength / 60).':'.(strlen($sessionLength % 60) == 1 ?  '0'.round($sessionLength  % 60).' m' : $sessionLength % 60 );

    }


    public static function GetInstance( Sessions $session, string $gameMode ): object
    {
        if ( self::$INSTANCE == NULL )
        {
            self::$INSTANCE = new self();  
            self::$session  = $session;
            self::$gameMode = $gameMode;
            
            
            self::Load();                         
            self::setCoocies();
        }               
        return self::$INSTANCE;
    }
}