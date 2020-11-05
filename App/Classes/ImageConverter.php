<?php
/*----------------------------------
    Mnyerczán Sándor
    
    Bináris képállományt tömörítő és
    Base64-re konvertáló osztály

    -- 2020.04.02 -- 
  --------------------------------- 
*/
namespace App\Classes;

use DateTime;
use DomainException;
use InvalidArgumentException;
use RangeException;
use ReflectionException;
use RuntimeException;

class ImageConverter
{

    private $bin,
            // A bin és cmpBin binárisokat át kell konvertálni a BTB64 függvénnyel, hogy
            // beágyazható legyen!!!
            $cmpBin,
            $origin,      
            $tmpName,
            $mime,            
            $requiredSize,
            $tmp = TMP_PATH;    
         
    /** 
     *  A getter mindig megkapja a kért nevet, még akkor is, ha az nem létezik.
     * Ilyenkor érdemes lehet kivételt dobni.
     */
    function __get($name)
    {
        switch($name) {
            case 'bin':     return $this->bin;    break;
            case 'cmpBin':  return $this->cmpBin; break;
            case 'origin':  return $this->origin; break;
            default: throw new InvalidArgumentException("Not exist the named variable: \"{$name}\"");
        }
    }

    /**
     * @param $path Path of the file
     * @param $mime Mime type of the file. Expected type: png, jpeg, jpg, gif,bmp?
     * @param $requireSize If send $mime, required size add size of compressing image. Value beetwen 0 and 100
     */
    public function __construct(string $path, ?string $mime = null) 
    {   
        if(!is_file($path))
            throw new InvalidArgumentException("The file does not exists at ".$path); 
        
        $this->tmpName     = $path;
        $this->mime         = $mime ?? 'image/jpg';        
                

        if (is_file($path)) {
            $this->bin      = file_get_contents($this->tmpName);
            $this->origin   = addslashes(base64_encode($this->bin));                                                           
        }        

        $this->requiredSize = (2**16) / strlen($this->bin);


        $this->compress();        
    }
          
    /**
     * ComressImage
     * 
     */
    private function compress()
    {      
        $compressedImage = null;
        

        //mime típus szerint konvertálunk.
        switch($this->mime)
        {
            case 'image/jpeg': $compressedImage  = imagecreatefromjpeg($this->tmpName); break;
            case 'image/jpg' : $compressedImage  = imagecreatefromjpeg($this->tmpName); break;
            case 'image/png' : $compressedImage  = imagecreatefrompng ($this->tmpName); break;
            case 'image/gif' : $compressedImage  = imagecreatefromgif ($this->tmpName); break;
            case 'image/bmp' : $compressedImage  = imagecreatefrombmp ($this->tmpName); break;
            default: throw new InvalidArgumentException("Invalid mime type getted: ".$this->mime);
        }   
               
        /**
         * 
         * 
         * Imagic extension telepítve, de nem látja a php..............
         * 
         * 
         * 
         */
        // $imagick = new Imagick();        
        
        
        // a tömörített kép kép generálása, hogy egyidőben több programpéldány
        // ne ugyan azt a fájlt akarja feldolgozni.            
        $fileName = md5(rand(0, 1000000)).'.jpeg';
                    
        # Mappa létezésének leellenőrzése
        if (!is_dir($this->tmp))
            if (!mkdir($this->tmp, 0777))                                 
                throw new RuntimeException("Permission denied in {$this->tmp} !");

        //Tömörítés. Sajnos csak file létrehozással tudom eddig.
        if (!imagejpeg($compressedImage, $this->tmp.$fileName, $this->requiredSize)) 
            throw new RangeException("failed to open stream: Permission denied in ".$this->tmp.$fileName);

        //létrehozott tömörített állomány beolvasása.            
        $this->cmpBin = file_get_contents($this->tmp.$fileName);
                            
        //tömörített állomány törlése a fájlrendszerből
        if(!unlink($this->tmp.$fileName))
            throw new DomainException("Cant't delete tmp fil from filesystem at: ".$this->tmp.$fileName);                    
    }        

    /**
     * Static method
     * @param $binary 
     * 
     * @return base64 coded fife
     */
    public static function BTB64($binary)
    {
        return addslashes(base64_encode($binary)); 
    }   

    
}
