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
use ReflectionException;
use RuntimeException;

class ImageConverter
{

    private $bin,
            $cmpBin,
            $origin,      
            $tmp_name,
            $mime,            
            $requiredSize,
            $tmp = TMP_PATH;    
         

    /** 
     *  A getter mindig megkapja a kért nevet, még akkor is, ha az nem létezik.
     * Ilyenkor érdemes lehet kivételt dobni.
     */
    function __get($name)
    {
        switch($name)
        {
            case 'bin':     return $this->bin;    break;
            case 'cmpBin':  return $this->cmpBin; break;
            case 'origin':  return $this->origin; break;
            default: throw new InvalidArgumentException("Not exist the named variable");
        }
    }








    /**
     * @param $path Path of the file
     * @param $mime Mime type of the file. Expected type: png, jpeg, jpg, gif,bmp?
     * @param $requireSize If send $mime, required size add size of compressing image. Value beetwen 0 and 100
     */
    public function __construct(string $path, string $mime = null, $requiredSize = 15) 
    {   
        
        $this->tmp_name     = $path;
        $this->mime         = $mime ? $mime : 'text/jpg';
        $this->requiredSize = $requiredSize ? $requiredSize : null;
        


        if ($requiredSize > 100 || $requiredSize < 0)
        {
            throw new InvalidArgumentException('Value of required size argument is invalid');
        }

        if (is_file($path))
        {
            $this->bin      = file_get_contents($this->tmp_name);
            $this->origin   = addslashes(base64_encode($this->bin));     

            if ($mime)
            {                
                if ($error = $this->CompressImage())            
                {                 
                    throw new InvalidArgumentException($error);             
                }                
            }
        }        

        return true;
    }






          
    /**
     * ComressImage
     * 
     * @return int 0 if compressing is successfull or else if unsuccessful
     * 
     * errno:
     * 
     *  1   Can't create file on APPLICATION/TMP_PATH/
     *  2   Unlink unsuccesfull
     *  3   Mime type is invalid
     *  4   Doesent exists Tmp dir
     */
    private function CompressImage()
    {      
        $compressedImage = null;

        //mime típus szerint konvertálunk.
        switch($this->mime)
        {
            case 'image/jpeg': $compressedImage  = imagecreatefromjpeg($this->tmp_name); break;
            case 'image/jpg' : $compressedImage  = imagecreatefromjpeg($this->tmp_name); break;
            case 'image/png' : $compressedImage  = imagecreatefrompng ($this->tmp_name); break;
            case 'image/gif' : $compressedImage  = imagecreatefromgif ($this->tmp_name); break;
            case 'image/bmp' : $compressedImage  = imagecreatefrombmp ($this->tmp_name); break;
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
            {                                    
                throw new RuntimeException("Permission denied in {$this->tmp} !");
                return 4;
            }            

        //Tömörítés. Sajnos csak file létrehozással tudom eddig.
        if (!imagejpeg($compressedImage, $this->tmp.$fileName, $this->requiredSize)) 
            return 1;

        //létrehozott tömörített állomány beolvasása.            
        $this->cmpBin = file_get_contents($this->tmp.$fileName);
                            
        //tömörített állomány törlése a fájlrendszerből
        if(!unlink($this->tmp.$fileName))
            return 2;
        
        return 0;
    
        
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
