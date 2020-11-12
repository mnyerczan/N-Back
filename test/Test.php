<?php

namespace Test;

/**
 * Abstract debug osztály. A test() metódus hívására alapértelmezetten 
 * létrehoz egy mappát, a tesztelt osztly nevével és egy fájlt, a tezstelt
 * függvény nevével a ~/.local/share mappában, ahova feljegyzi az egyes 
 * függvények lefutási idejét. Ebből átlagot számol, majd kiírja a teszteset 
 * eredményéhez, pontosítva az algoritmus futási időigényét..
 * 
 */
abstract class Test
{

    /**
     * --------------------------------------------------------------
     * Kiírja a függvény végrehajtásának várt és tényleges
     * eredményét.
     * --------------------------------------------------------------
     * 
     * @param object $obj A tesztelni kívánt osztály objektuma
     * @param string $function A tesztelni kívánt osztály tagfüggvénye
     * @param array $params A kapott függvény opcionális paraméterei
     * @param bool $expect A várt eredmény
     * 
     */
    public static function test($obj, string $function, ?array $params = [], $expect = true)
    {   

        // Bejövő adatok ellenőrzése
        if (!in_array($function, get_class_methods($obj)) ) {
            echo "\e[1;37;41m".PHP_EOL.PHP_EOL."Callable should be a callable function, or an array with two members: \"0\" an object of a class,",
            PHP_EOL."and a \"1\", a member function of the \"0\" element on line ".debug_backtrace()[0]["line"]."!".PHP_EOL."\e[0m".PHP_EOL;
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
        if (is_object($obj))
            $objName = get_parent_class($obj) ? get_parent_class($obj) : get_class($obj);
        else 
            $objName = $obj;
        
        $fileParams = self::tfile($dtime, $objName, $function);
        if (!is_array($fileParams)) {
            echo "\e[1;37;41m".PHP_EOL.PHP_EOL;
            echo $fileParams;
            echo PHP_EOL."\e[0m".PHP_EOL;
            die;
        }
        $str = sprintf(
            "%3d |\033[1m%-12s \e[0m|", 
            debug_backtrace()[0]["line"], 
            strlen($function) <=  10 ? $function : substr($function, 0, 10).".."
        );    
        
        // Meghatározzuk, hogy a kívánt értéken felül van-e az átlag.
        // Ha igen, piros kiemeléssel jelenítjük meg.  
        $str .= "avg: ";
        if ($fileParams["avg"] > 1) {
            // Rossz hatásfokú
            $str.=sprintf("'\e[1;37;41m%5.3f\e[0m'", $fileParams["avg"]);
        }
        else {
            // jó hatásfokú
            $str.=sprintf("'%5.3f'", $fileParams["avg"]);
        }

        $str .=sprintf("ms |sample:%-4d",$fileParams["count"]);    
        $str .=sprintf("|current: '%5.3f'ms | ", $dtime);    
        
        // Ha a tesztel metódus visszatérési értéke object
        // parsoljuk azt srtinggé a megjelenítéshez.
        if ( is_object($stmt))
            $stmt = get_class($stmt);

        // A kívánt és a kapott eredmény függvényében piros, vagy zöld
        // háttérrel íratjuk ki az eredményt. Ha a függvény nem bool értékkel
        // tér vissza, akkor azt írja ki az algoritmus.
        if ($stmt == $expect) {        
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
    private static function tfile(float $dtime, string $objName, string $fname)
    {
        $sum = $dtime;
        $counter = 1;
        $user = posix_getpwuid(posix_geteuid())['name'];
        $path = "/home/{$user}/.local/share/testStatistics/{$objName}/{$fname}.txt";
        
        if (!is_dir("/home/{$user}/.local/share/testStatistics/")) {
            if (!mkdir("/home/{$user}/.local/share/testStatistics", 0755)) {
                return "Can't create folder /home/{$user}/.local/share/testStatistics/{$objName}. Access denied for '".
                posix_getpwuid(posix_geteuid())['name']."'. File owner '".posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";
            }      
        }
        // Ha nem létezik a kapott osztályhoz tartozó mappa,
        // megkísérli létrehozni. Ha nincs jogosultság, akkor
        // kiírja a hibaüzenetet és befejezi a végrehajtást.
        if (!is_dir("/home/{$user}/.local/share/testStatistics/{$objName}")) {
            if (!mkdir("/home/{$user}/.local/share/testStatistics/{$objName}", 0755)) {
                return "Can't create folder /home/{$user}/.local/share/testStatistics/{$objName}. Access denied for '".
                posix_getpwuid(posix_geteuid())['name']."'. File owner '".posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";
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
        if (!$resource)
            return "Can't create file {$path}. Access denied for '".posix_getpwuid(posix_geteuid())['name']."'. File owner '".
            posix_getpwuid(fileowner("/home/{$user}/.local/share/"))["name"]."'";        
        
        while(!feof($resource)) {
            if(($inp = fgets($resource)) != "") {
                $sum += unpack("L",$inp)[1];
                $counter++;        
            }
        }     

        fputs($resource, pack("L", $dtime).PHP_EOL);

        return [
            "avg" => round($sum / ($counter), 3), 
            "count" => $counter
        ];
    }
}