<?php

namespace App\Transport\Outbound;

class Response 
{
    public static function json(int $responseHttpCode, ?array $data): void
    {
        header('Content-Type: application/json');
  
        http_response_code($responseHttpCode);
        echo json_encode($data);
    }
}