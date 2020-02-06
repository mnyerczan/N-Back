<?php

namespace Model;

use DB\EntityGateway;

require_once APPLICATION.'DB/entityGateway.php';

class Sessions
{

    private
            $database,
            $sessions = [],
            $times ,
            $userID;

/**
 * @param int $userID   Identificer of user
 * 
 * Get last 10 session after 2019-01-01 00:00:00
 * @param int $askAllSession      default 0
 * 
 */
    function __construct( int $userID, int $askAllSession = 0 )
    {

        $this->database = EntityGateway::getDB();
        $this->userID   = $userID;

        if ( $askAllSession === 1 )
        {
            $sessions   = $this->getAllSessions();
        }        
        else
        {
            $sessions   = $this->getSessions();
        }
       
        $this->sessions = $this->Load( $sessions );
        $this->times    = $this->getTimes();
    }   


    public function __get($name)
    {
        switch ( $name )
        {
            case 'sessions':    return $this->sessions; break;
            case 'times':       return $this->times; break;
        }
    }

/**
 * @return array of stdClass object(s)
 */
    private function getSessions(): array
    {      
        /**
         * Only one day after from yesterday
         */
        return $this->database->getSessions( $this->userID );                
        
    }

/**
 * @return array of stdClass object(s)
 */
    private function getAllSessions(): array
    {    
        /**
         * Only one day after from yesterday
         */
        return $this->database->getSessions( $this->userID, '2019-01-01 00:00:00' );                        
        
    }

/**
 * @param array of stdClass object(s)
 */
    private function Load( array $sessions ): array
    {       
        /**
         * Ezzel a megoldással egyszerre két függvényből hívható le a tábla tartalma.
         */        
        if ( count( $sessions ) > 0 )
        {
            for ($i=0; $i < count( $sessions ); $i++) 
            { 
                $sessions[$i]->percent = round(
                    $sessions[$i]->correctHit * 100 / ($sessions[$i]->wrongHit + $sessions[$i]->correctHit));
            }
            return $sessions;
        }
        elseif ( @$_COOKIE['sessionUpload'] )
        {
            /**
             * Még le kell vizsgálni, hogy, ha a navbár is a Sessions-től kapja az adatokat, és egy elmentett régi játékot kap sütiből, azt ne jelenítse meg.
             */
            return [
                (object) [
                    'sessionLength' => $_COOKIE['sessionLength'] ,
                    'result'        => 0,
                    'wrongHit'      => $_COOKIE['wrongHit'] ,
                    'correctHit'    => $_COOKIE['correctHit'],
                    'level'         => $_COOKIE['level'],
                    'gameMode'      => $_COOKIE['gameMode'],
                    'ip'            => $_SERVER['REMOTE_ADDR'],
                    'timestamp'     => '1970-01-01 00:00:00',
                    'percent'       => round( (int)$_COOKIE['correctHit'] * 100 / (int)($_COOKIE['wrongHit'] + (int)$_COOKIE['correctHit']) )
                ]
            ];
        }
        else
        {        
            return [
                (object) [
                    'sessionLength' => '--',
                    'result'        => 0,
                    'wrongHit'      => '--',
                    'correctHit'    => '--',
                    'level'         => 1,
                    'gameMode'      => 'Position',
                    'ip'            => $_SERVER['REMOTE_ADDR'],
                    'timestamp'     => '1970-01-01 00:00:00',
                    'percent'       => '--'
                ]
            ];
        }
    }

    private function getTimes()
    {        
        $times = $this->database->getTimes( $this->userID )[0];

        $times->last_day = $times->last_day == NULL ? 0 : $times->last_day;
        $times->today = $times->today == NULL ? 0 : $times->today;
        $times->today_position = $times->today_position == NULL ? 0 : $times->today_position;

        return $times;
    }
    
}