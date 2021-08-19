<?php

namespace CleanCode\Chapter14;

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

    public function getErrorMessage(): string
    {
        $newer = 'This cannot be, because this can never be!!!';

        switch ($this->errorCode) {
            case ErrorCodeEnum::MISSING_STRING() :
                return 'Could not find string parameter for ' . $this->argument . '.' . PHP_EOL;
            case ErrorCodeEnum::MISSING_INTEGER() :
                return 'Could not find integer parameter for ' . $this->argument . '.' . PHP_EOL;
            case ErrorCodeEnum::INVALID_INTEGER() :
                return 'Invalid integer parameter: ' . $this->argument . '.' . PHP_EOL;
            case ErrorCodeEnum::MISSING_DOUBLE() :
                return 'Could not find double parameter for ' . $this->argument . '.' . PHP_EOL;
            case ErrorCodeEnum::INVALID_DOUBLE() :
                return 'Invalid double parameter: ' . $this->argument . '.' . PHP_EOL;
            case ErrorCodeEnum::UNEXPECTED_ARGUMENT() :
                return 'Argument "' . $this->argument . '" unexpected.' . PHP_EOL;
            case ErrorCodeEnum::OK() :
                throw new Exception($newer);
            default :
                throw new Exception($newer);
        }
    }
}
