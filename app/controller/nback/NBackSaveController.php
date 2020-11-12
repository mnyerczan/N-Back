<?php

namespace App\Controller\Nback;

use App\Core\GameController;
use App\Core\ViewRenderer;
use App\Services\User;
use App\Services\DB;
use App\Model\ViewParameters;

class NBackSaveController extends GameController
{
    public function __construct()
    {
        DB::setup();
        User::setup();
    }

    public function index()
    {
        extract($_POST);

        

        if ($wrongHit + $correctHit != 0)
            $percent = $correctHit / ($wrongHit + $correctHit) * 100;
        else 
            $percent = 0; 

        // A játékszint emeléséhez, szinten tartásához,
        //  vagy csökkentéséhez szükséges adat.
        if ($percent >= 80)
            $wrongSessions = 1;
        elseif ($percent >= 50)
            $wrongSessions = 0;
        else
            $wrongSessions = -1;


        $params = [
            ":userId" => User::$id,
            ":ip" => $_SERVER["REMOTE_ADDR"],
            ":level" => User::$level,
            ":correctHit" => $correctHit,
            ":wrongHit" => $wrongHit,
            ":sessionLength" => User::$seconds * User::$trials * 1000,        
            ":gameMode"  => User::$gameMode,
            // Elérte-e a 80%-ot, vagy 500 alatt volt-e
            ":sessionResult" => $wrongSessions
        ];

        DB::execute("CALL exportSession(:userId, :ip, :level, :correctHit, :wrongHit, :sessionLength, :gameMode, :sessionResult)", $params);
        $this->put("update", 1);
        $this->Response( new ViewParameters("", "application/json"));
    }


    public function feedback()
    {
        $wrongSessions =  DB::selectAll("SELECT * FROM `nbackSessions` ");
        if ($wrongSessions == 2) {
            
        }
    }
}