<?php

namespace App\Utils\Exceptions;

class EnvironmentVariableDoesNotExist extends \RuntimeException
{
    public function __construct(string $filename)
    {
        $errorMessage = "File with name {$filename} does not exist";
        parent::__construct($errorMessage);
    }
}
