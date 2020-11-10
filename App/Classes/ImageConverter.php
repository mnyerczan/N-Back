<?php
/*----------------------------------
    Mnyerczán Sándor
    
    Bináris képállományt tömörítő és
    Base64-re konvertáló osztály

    -- 2020.04.02 -- 
    Add Imagick
    -- 2020.11.09 --
  --------------------------------- 
*/
namespace App\Classes;


use InvalidArgumentException;
use LengthException;


class ImageConverter
{ 
    private             
            $bin,
            $origin,      
            $filePath,         
            $maxSize = 2**16,            
            $imagick;                   

    /**
     * @param $path Path of the file
     */
    public function __construct(string $path) 
    {   
        $this->filePath = $path;

        if(!is_file($path))
            throw new InvalidArgumentException("The file does not exists at ".$path); 
                                
        if (is_file($path)) {
            $this->bin   = file_get_contents($this->filePath);          
            $this->origin   = addslashes(base64_encode($this->bin));                                                           
        }        
        
        $imgDatas       = getimagesize($this->filePath);
        $this->width    = $imgDatas[0];
        $this->height   = $imgDatas[1];
        // MySQL Blob type    
        $this->imagick = new \Imagick($this->filePath);           
        $this->bin = file_get_contents($this->filePath);
    }
          
    /**
     * ComressImage
     * @param float $width A kép szélessége pixelben
     * @param float $height A kép magassága pixelben
     * 
     */
    public function compress(float $width = 250, float $height = 250): void
    {
        $size = $this->imagick->getImageLength();        
        $quotient = "";
        if ($this->maxSize < $size) {
            $quotient = (int)(2**16 / $size * 100);
        }
        elseif($this->maxSize > $size + 10000) {
            $quotient = 100;
        }
        // A thumbnail függvény mindig a legkisebb oldalhosszhoz
        // igaztja a másikat, ha a harmadik paraméter true. Így
        // méretarányos marad a kép.
        $this->imagick->thumbnailImage($width, $height, true);
        
        if(($size = $this->imagick->getImageLength()) > $this->maxSize)
            throw new LengthException("The file size '".$size."' bytes is to large!");

        $this->bin = $this->imagick->getImageBlob();
    }        

    /**
     * Static method
     * @param $binary 
     * 
     * @return base64 coded fife
     */
    public static function BTB64($binary): string
    {
        return addslashes(base64_encode($binary)); 
    }   
  
    /** 
     *  A getter mindig megkapja a kért nevet, még akkor is, ha az nem létezik.
     * Ilyenkor érdemes lehet kivételt dobni.
     * @param $name The name of the needed property
     */
    function __get($name)
    {
        switch($name) { 
            case 'bin':  return $this->bin; break;
            case 'origin':  return $this->origin; break;
            default: throw new InvalidArgumentException("Not exist the named variable: \"{$name}\"");
        }
    }

}
