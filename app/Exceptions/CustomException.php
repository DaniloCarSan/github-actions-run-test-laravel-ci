<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{

    public function __construct(  string $message = "", private ?int $httpCode, private $data = null)
    {
        parent::__construct($message);
    }

    public function getHttpCode(): ?int {
        return $this->httpCode;
    }

    public function getData() {
        return $this->data;
    }
}