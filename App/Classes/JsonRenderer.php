<?php

namespace App\Classes;


class JsonRenderer
{
    private string $outputString;
    private int $tabCounter = 1;

    public function Emit($models)
    {
        $this->outputString = "{\n\t\"responseText\":";
        $this->GenerateJons($models, ++$this->tabCounter);
        $this->outputString .= "\n}";        

        // Megoldható volna eg sorban az egész, de az olvashatóság kedvéért kell a formázás.
        #return json_encode(["responseText" => $models]);

        return $this->outputString;
    }


    /**
     * A kapott adatszerkezetet alakítja Json formátumra.
     */
    private function GenerateJons($models)
    {        
        $this->outputString .= "{\n".$this->generateTabulators();
        $indexer = 0;


        foreach ($models as $key => $value) 
        {
            if (is_array($value))
            {
                $this->GenerateJons($models[$key], ++$this->tabCounter);
            }

            $this->outputString .= "\"".$key."\":\"".$value."\"";

            if ($indexer++ < count($models) -1 )
            $this->outputString .= ",\n";
        }

        $this->tabCounter--;
        $this->outputString .= "\n".$this->generateTabulators()."}";
    }

    /**
     * A tabulátorok beillesztését, a Json formázását végző függvény.
     */
    private function generateTabulators()
    {
        $tabulators = "";
        for ($i=0; $i < $this->tabCounter; $i++) 
        {             
            $tabulators .= "\t";
        }  
        return $tabulators;
    }
}