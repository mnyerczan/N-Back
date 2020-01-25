<?php

namespace Model;

use DB\EntityGateway;

require_once APPLICATION.'DB/entityGateway.php';

class Sessions
{

    private $database,
            $sessions,
            $times,
            $userID;

    function __construct( $userID )
    {
        $this->database = EntityGateway::getDB();
        $this->userID = $userID;

        $this->getSessions();
        $this->getTimes();
    }   


    public function __get($name)
    {
        switch ( $name )
        {
            case 'sessions': return $this->sessions; break;
            case 'times': return $this->times; break;
        }
    }

    private function getSessions()
    {
        $sessions = $this->database->getSessions( $this->userID );

        for ($i=0; $i < count( $sessions ); $i++) 
        { 
            $sessions[$i]->percent = round(
                $sessions[$i]->correct_hit * 100 / ($sessions[$i]->wrong_hit + $sessions[$i]->correct_hit));

            unset($sessions[$i]->correct_hit);
            unset($sessions[$i]->wrong_hit);
        }

        $this->sessions = $sessions;
    }

    private function getTimes()
    {        
        $times = $this->database->getTimes( $this->userID )[0];

        $times->last_day = $times->last_day == NULL ? 0 : $times->last_day;
        $times->today = $times->today == NULL ? 0 : $times->today;
        $times->today_position = $times->today_position == NULL ? 0 : $times->today_position;

        $this->times = $times;
    }
    
}