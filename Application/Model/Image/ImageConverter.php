<?php
/*----------------------------------
    Mnyerczán Sándor
    
    Bináris képállományt tömörítő és
    Base64-re konvertáló osztály

    -- 2020.04.02 -- 
  --------------------------------- 
*/

namespace Model\Image;

use InvalidArgumentException;

class ImageConverter
{

    private $bin,
            $cmpBin,
            $origin,      
            $tmp_name,
            $mime,            
            $requiredSize,
            $tmp = TMP_PATH;    
     
    
    function __get($name)
    {
        switch($name)
        {
            case 'bin':     return $this->bin;    break;
            case 'cmpBin':  return $this->cmpBin; break;
            case 'origin':  return $this->origin; break;
        }
    }
            
    private function compressImage()
    {      
        $compressedImage = null;

        //mime típus szerint konvertálunk.
        switch($this->mime)
        {
            case 'image/jpeg': $compressedImage  = imagecreatefromjpeg($this->tmp_name); break;
            case 'image/png' : $compressedImage  = imagecreatefrompng ($this->tmp_name); break;
            case 'image/gif' : $compressedImage  = imagecreatefromgif ($this->tmp_name); break;
            case 'image/bmp' : $compressedImage  = imagecreatefrombmp ($this->tmp_name); break;
        }
               
        if ($compressedImage)
        {            
            // a tömörített kép nén generálása, hogy egyidőben több programpéldány
            // ne ugyan azt a fájlt akarja feldolgozni.            
            $fileName = md5(rand(0, 1000000)).'.jpeg';
            
            //Tömörítés. Sajnos csak file létrehozással tudom eddig.
            if (!imagejpeg($compressedImage, $this->tmp.$fileName, $this->requiredSize)) 
                return 'Can\'t create file on '.$this->tmp.$fileName.'!';

            //létrehozott tömörített állomány beolvasása.
            $this->cmpBin = file_get_contents($this->tmp.$fileName);
                                
            //tömörített állomány törlése a fájlrendszerből
            if(!unlink($this->tmp.$fileName))
                return 'Unlink unsuccesfull';
            
            return 0;
        }

        return 'Mime type is invalid';
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
            return false;
        }

        if (is_file($path))
        {
            $this->bin          = file_get_contents($this->tmp_name);
            $this->origin       = addslashes(base64_encode($this->bin));     

            if ($mime)
            {
                if ($error = $this->compressImage())            
                {
                    var_dump($error);
                    throw new InvalidArgumentException($error);
                    return false;
                }                
            }
        }        

        return true;
    }
    
    /**
     * Static method
     * @param $binary 
     * 
     * return base64 coded fife
     */
    public static function BTB64($binary)
    {
        return addslashes(base64_encode($binary)); 
    }   

    
}
