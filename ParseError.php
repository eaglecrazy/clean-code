<?php

namespace CleanCode;

use MyCLabs\Enum\Enum;

class ParseError
{
    private Enum $errorCode;
    private string $argument;

    public function __construct(Enum $errorCode, string $argument)
    {
        $this->errorCode = $errorCode;
        $this->argument = $argument;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getArgument()
    {
        return $this->argument;
    }
}
