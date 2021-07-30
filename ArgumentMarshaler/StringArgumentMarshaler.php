<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;
use CleanCode\ErrorCodeEnum;
use CleanCode\Exceptions\ArgsException;

class StringArgumentMarshaler implements ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        if (strlen($s) <= 2) {
            throw new ArgsException(ErrorCodeEnum::MISSING_STRING(), $s);
        }

        $this->stringValue = substr($s, 2);
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->stringValue;
    }
}
