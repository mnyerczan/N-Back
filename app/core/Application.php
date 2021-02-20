<?php

namespace App\Core;


use App\Controller\Errors\ExceptionErrorController;
use App\Http\Router;
use App\Services\DB;
use App\Services\User;


class Application 
{
    public static function run($cleanedUri)
    {
        // Ha nincs jogosultság elérni a /var/lib/apache2/sessions/... fájlt, error view.
        if(@!session_start()) {
            $cleanedUri = "/sessionError";
            return Router::route($cleanedUri);
        }        

        try {
            if (!DB::setup()) {
                $cleanedUri = "/databaseError";
            } else {
                User::setup();
            }  

            Router::route($cleanedUri);
        } catch (\Exception $e) {
            (new ExceptionErrorController)->exception($e);
        }        
    }    
}