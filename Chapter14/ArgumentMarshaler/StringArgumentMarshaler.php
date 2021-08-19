<?php

namespace CleanCode\Chapter14\ArgumentMarshaler;

use CleanCode\Chapter14\ArgumentMarshaler;
use CleanCode\Chapter14\ErrorCodeEnum;
use CleanCode\Chapter14\ArgsException;

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
