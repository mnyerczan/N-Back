#!/usr/bin/php7.4
<?php

chdir('/var/www/html/NBack');
error_reporting(0);

// Böngésző cash kezelő konstans.
#define('RELOAD_INDICATOR'	, '' );
define('RELOAD_INDICATOR'	, date('s') );
// Program mappa
define('APPLICATION'		, 'App/');
// Root directory. Must be at least '/' !
define('APPROOT'			, "/NBack");
// Temp mappa útvonal
define('TMP_PATH'			, APPLICATION.'Tmp/');
// Config path
define('CONF_PATH'			, 'config.json');
// HTTP protocol
define('HTTP_PROTOCOL'      , 'http://');
define('BACKSTEP', '');

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';



// Autoloader
spl_autoload_register(function($className) {
    // $classNema for debug...
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);    
    include_once $_SERVER['DOCUMENT_ROOT'] . $className . '.php';;
});


/**
 * --------------------------------------------------------------
 * tets Kiírja a függvény végrehajtásának várt és tényleges
 * eredményét.
 * --------------------------------------------------------------
 * 
 * @param object $obj A tesztelni kívánt osztály objektuma
 * @param string $function A tesztelni kívánt osztály tagfüggvénye
 * @param array $params A kapott függvény opcionális paraméterei
 * @param bool $result A várt eredmény
 * 
 */
function test(object $obj, string $function, ?array $params = [], bool $result = true)
{    
    // Bejövő adatok ellenőrzése
    if (!(is_object($obj) && in_array($function, get_class_methods($obj))) ) {
        echo "\e[1;37;41m".PHP_EOL.PHP_EOL."Callable should be a callable function, or a array with two members: \"0\" an object of a class,".PHP_EOL."and a \"1\", a member function of the \"0\" element on line ".debug_backtrace()[0]["line"]."!".PHP_EOL."\e[0m".PHP_EOL;
        if (!is_object($obj)) {
            echo "\e[1;37;41m".PHP_EOL.PHP_EOL."{$obj} is not a object!".PHP_EOL."\e[0m".PHP_EOL;
        }
        else {
            echo "\e[1;37;41m".PHP_EOL.PHP_EOL."{$function} is not a member function of object (".get_class($obj).")!".PHP_EOL."\e[0m".PHP_EOL;
        }
        die;
    }         
    
    // A hrtime az aktuális rendszeridőt adja vissza nanomásodpercben
    $stime = hrtime(true);
    $stmt = call_user_func([$obj, $function], ...$params); 
    $dtime = (hrtime(true) - $stime) / 1000000.0;

    // Ha a tesztelt osztálynak van szülője, vagyis tulajdonképpen a szülő
    // osztályt teszteljük, akkor a szülő osztály nevén fog szerepelni a 
    // generált mappa, különben magát a kapott osztályt teszteljük, így 
    // annak a nevén kell szerepelnie.
    $objName = get_parent_class($obj) ? get_parent_class($obj) : get_class($obj);
    
    $fileParams = tfile($dtime, $objName, $function);
    if (!is_array($fileParams)) {
        echo "\e[1;37;41m".PHP_EOL.PHP_EOL;
        echo $fileParams;
        echo PHP_EOL."\e[0m".PHP_EOL;
        die;
    }
    $str = sprintf("|\033[1m%4d\033[0m| %-9s ", debug_backtrace()[0]["line"], strlen($function) <=  7 ? $function : substr($function,0,7)."..");    
    // Ha a függvény végrehajtási idő magasabb 100 microsecundumnál, 
    // piros kiemeléssel írja ki.

    // Meghatározzuk, hogy a kívánt értéken felül van-e az átlag.
    // Ha igen, piros kiemeléssel jelenítjük meg.       
    if ($fileParams["avg"] > 1) {
        // Rossz hatásfokú
        $str.=sprintf("\e[1;37;41m'%5.3f'\e[0m", $fileParams["avg"]);
    }
    else {
        // jó hatásfokú
        $str.=sprintf("'%5.3f'", $fileParams["avg"]);
    }

    $str .=sprintf("ms items: %4d ",$fileParams["count"]);    
    $str .=sprintf("| act: '%5.3f'ms | ", $dtime);    
    

    // A kívánt és a kapott eredmény függvényében piros, vagy zöld
    // háttérrel íratjuk ki az eredményt. Ha a függvény nem bool értékkel
    // tér vissza, akkor azt írja ki az algoritmus.
    if ($stmt == $result) {
        if (gettype($stmt) == "boolean" || $stmt == 0 || $stmt == 1 || $stmt == "")
            $stmt = "true";        
        $str.= "\033[1m{$stmt} \e[0m";
    }
    else {
        if (gettype($stmt) == "boolean" || $stmt == 0 || $stmt == 1 || $stmt == "")
            $stmt = "false";
        $str.= "\e[0;31m\033[1m{$stmt} \e[0m";
    }        
    echo $str.PHP_EOL;
}

/**
 * --------------------------------------------------------------------------------
 * Teszt fájlkezelő függvény. 
 * A kapot osztály nevével létrehoz egy mappát a Test/statistics 
 * almappában és abban hozza létre az egyes függvények futásidejének
 * adatait tartalmazó fájlokat. A fájlok a függvény nevén szerepelnek.
 * --------------------------------------------------------------------------------
 * 
 * @param float $dtime Differencia az algoritmus futása és vége közötti időpontokból
 * @param string $objname   Az osztályhoz tartozó mappa nevéhez
 * @param string $fname     A fájl a függvény nevén fog szerepelni.
 * @return array|string            A kalkulált átlag "avg" és az esetek száma "count"
 * @throws DomainException  A futtató felhasználónak nincs jogosultsága a fájlredszer művelethez.
 * 
 */
function tfile(float $dtime, string $objName, string $fname)
{
    $sum = 0;
    $counter = 1;
    $user = posix_getpwuid(posix_geteuid())['name'];
    $path = "/home/{$user}/.local/share/testStatistics/{$objName}/{$fname}.txt";
    
    if (!is_dir("/home/{$user}/.local/share/testStatistics/")) {
        if (!mkdir("/home/{$user}/.local/share/testStatistics", 0755)) {
            return "Can't create folder /home/{$user}/.local/share/testStatistics/{$objName}. Access denied for '". posix_getpwuid(posix_geteuid())['name']."'. File owner '".posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";
        }      
    }
    // Ha nem létezik a kapott osztályhoz tartozó mappa,
    // megkísérli létrehozni. Ha nincs jogosultság, akkor
    // kiírja a hibaüzenetet és befejezi a végrehajtást.
    if (!is_dir("/home/{$user}/.local/share/testStatistics/{$objName}")) {
        if (!mkdir("/home/{$user}/.local/share/testStatistics/{$objName}", 0755)) {
            return "Can't create folder /home/{$user}/.local/share/testStatistics/{$objName}. Access denied for '". posix_getpwuid(posix_geteuid())['name']."'. File owner '".posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";
        }            
    }    

    if (!is_file($path))
        $resource = fopen($path,"x+");
    else {
        $resource = fopen($path,"r+");
    }

    // Ha nem sikerül megnyitni, vagy ha a fájl nem létezik,
    // azt létrehozni, kiírja a hibaüzenetet és befejezi a 
    // végrehajtást.    
    if (!$resource) {        
        return "Can't create file {$path}. Access denied for '". posix_getpwuid(posix_geteuid())['name']."'. File owner '".posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";        
    }

    while(!feof($resource)) {
        $sum +=  (float)fgets($resource);
        $counter++;
    }

    fputs($resource, $dtime.PHP_EOL);

    return [
        "avg" => round(($sum + $dtime) / ($counter + 1), 3), 
        "count" => $counter + 1
    ];
}