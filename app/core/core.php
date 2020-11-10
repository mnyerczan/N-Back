<?php


// Ha nincs jogosultság elérni a /var/lib/apache2/sessions/... fájlt, error view.
if(@!session_start()) {
    // self::$addRoute('/(?<controller>sessionError)/?', "App\Controller\SessionError", 'get');   
    $cleanedUri = "/sessionError";
}        

try {
    if (!App\Services\DB::setup()) {
        $cleanedUri = "/databaseError";
    } else {
        App\Services\User::setup();
    }  

    App\Http\Router::route($cleanedUri);
} catch (Exception $e) {
    (new App\Controller\Errors\ExceptionErrorController())->index($e);
}
