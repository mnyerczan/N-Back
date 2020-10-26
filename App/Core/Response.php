<?php

namespace App\Core;


class Response 
{
    private string 
                    $body,
                    $reasonPhrase;
    private array   $headers;
    private int     $statusCode;    


    public function __construct(string $body, array $headers, int $statusCode, string $reasonPhrase)
    {
        $this->body         = $body;
        $this->headers      = $headers;
        $this->statusCode   = $statusCode;
        $this->reasonPhrase = $reasonPhrase;

    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'body':        return $this->body;
            case 'headers':     return $this->headers;
            case 'statusCode':  return $this->statusCode;
            case 'reasonPhrase':return $this->reasonPhrase;
        }
    }
}