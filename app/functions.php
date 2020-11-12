<?php



// Autoloader
spl_autoload_register(function($className) {
    $path = "";
    $units = explode("\\", $className);
    for($i = 0; $i < count($units); $i++) {
        if ($i < count($units) -1 ) {
            $path .= strtolower($units[$i][0]).substr($units[$i], 1);        
            $path .= DIRECTORY_SEPARATOR;            
        }        
        else
            $path .= $units[$i];
     }           
    require getcwd() ."/" . $path . ".php";
});


// Cause the explode function cut to two part a string with a slash, and
// we need it works if the url contains two slash, we substract 2 from the result.
function calcBackStep($cleanedUri, $backToRoot = ""): string
{
    
    $pieces = substr_count(substr($cleanedUri, 1), "/");    
    
    for ( $i = 0; $i < $pieces; $i++ ){
        $backToRoot.= "../";
    }  

    return $backToRoot;
}

set_error_handler(function(){
    App\Http\Router::route(CLEANED_URI);
});