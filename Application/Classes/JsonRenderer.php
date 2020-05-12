<?php

class JsonRenderer
{
    private $outputString;
    private $tabCounter = 1;

    public function Emit($models)
    {
        $this->outputString = "{\n\t\"responseText\":";
       
            $this->GenerateJons($models, ++$this->tabCounter);

        $this->outputString .= "\n}";

        return $this->outputString;
    }


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