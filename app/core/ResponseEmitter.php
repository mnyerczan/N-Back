<?php

namespace App\Core;


class ResponseEmitter
{
    public function emit(Response $response)
    {
        $this->emitStatusLine($response->statusCode, $response->reasonPhrase);
        $this->emitHeaders($response->headers);
        $this->emitBody($response->body);
    }


    
    private function emitStatusLine(int $statusCode, string $raseonPhrase)
    {        
        header(sprintf("HTTP/1.1 %d%s", $statusCode, $raseonPhrase), true, $statusCode);
    }

    private function emitHeaders(array $headers)
    {
        foreach ($headers as $key => $value) 
        {
            header(sprintf("%s:%s",$key,$value));    
        }
    }

    private function emitBody(string $body)
    {
        echo $body;
    }
}