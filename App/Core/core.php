<?php


// Ha nincs jogosultság elérni a /var/lib/apache2/sessions/... fájlt, error view.
if(@!session_start()) {
    // self::$addRoute('/(?<controller>sessionError)/?', "App\Controller\SessionError", 'get');   
    $cleanedUri = "/sessionError";
}        


App\DB\DB::setup();
App\Model\User::setup();  


App\Http\Router::route($cleanedUri);	
