<?php


// Ha nincs jogosultság elérni a /var/lib/apache2/sessions/... fájlt, error view.
if(@!session_start()) {
    // self::$addRoute('/(?<controller>sessionError)/?', "App\Controller\SessionError", 'get');   
    $cleanedUri = "/sessionError";
}        

try {
    if (!App\DB\DB::setup()) {
        $cleanedUri = "/databaseError";
    } else {
        App\Model\User::setup();
    }  

    App\Http\Router::route($cleanedUri);
} catch (Exception $e) {
    (new App\Controller\Errors\ExceptionErrorController())->index($e);
}
