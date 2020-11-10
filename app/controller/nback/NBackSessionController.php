<?php

namespace App\Controller\Nback;


use App\Core\GameController;
use App\Model\ViewParameters;
use App\Services\User;


class NBackSessionController extends GameController
{
    function __construct()
    {
        parent::__construct();
        
        $this->SetDatas();
        $this->datas["jsonOptions"] = $this->jsonOptions();
      
    }

    public function index()
    {           
        $this->Response( 
            $this->datas, new ViewParameters(
                'nback',
                'text/html',
                "nback",
                "Nback",
                "Nback") 
        );
    }

    protected function jsonOptions()
    {
        if (User::$id) {
            $jsonOptions = json_encode(
                [
                    "level" => User::$level,
                    "trials" => User::$trials,
                    "seconds" => User::$seconds,
                    "gameMode" => User::$gameMode,
                    "eventLength" => User::$eventLength,
                    "color" => User::$color
                ]);            
        } 
        elseif (!isset($_COOKIE["gameMode"])) {
            // Ha véletlenül anélkül ugrana a /nback url-re, hogy be lenne állítva süti
            $this->Response([], new ViewParameters("redirect:".APPROOT."/nback"));
        } 
        else {
            $jsonOptions = json_encode(
                [
                    "level" => $_COOKIE["level"],
                    "trials" => $_COOKIE["trials"],
                    "seconds" => $_COOKIE["seconds"],
                    "gameMode" => $_COOKIE["gameMode"],
                    "eventLength" => $_COOKIE["eventLength"],
                    "color" => $_COOKIE["color"]
                ]);
        }
        return $jsonOptions;
    }
}