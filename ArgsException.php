<?php

namespace CleanCode;

use Exception;
use MyCLabs\Enum\Enum;

class ArgsException extends Exception
{
    private Enum $errorCode;
    private string $argument;

    public function __construct(Enum $errorCode, string $argument)
    {
        $this->errorCode = $errorCode;
        $this->argument = $argument;

        parent::__construct();
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
