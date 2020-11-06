<?php

namespace App\Controller\Nback;

use App\Core\GameController;
use App\Core\ViewRenderer;
use App\DB\DB;
use App\Model\User;
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
            $result = 1;
        elseif ($percent >= 50)
            $result = 0;
        else
            $result = -1;


        $params = [
            ":userId" => User::$id,
            ":ip" => $_SERVER["REMOTE_ADDR"],
            ":level" => User::$level,
            ":correctHit" => $correctHit,
            ":wrongHit" => $wrongHit,
            ":sessionLength" => User::$seconds * User::$trials * 1000,        
            ":gameMode"  => User::$gameMode,
            // Elérte-e a 80%-ot, vagy 500 alatt volt-e
            ":result" => $result
        ];

        DB::execute("CALL exportSession(:userId, :ip, :level, :correctHit, :wrongHit, :sessionLength, :gameMode, :result)", $params);

        $this->Response(["update" => 1], new ViewParameters("", "application/json"));
    }


    public function feedback()
    {
        DB::select("SELECT * FROM `nbackSessions` ");
    }
}