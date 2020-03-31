<?php

namespace Model\Image;

class ImageConverter
{

    private $iBin;

    
    public function __construct($iBin)
    {
        $this->iBin = base64_encode( $iBin );
    }
}