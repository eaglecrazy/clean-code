<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;
use CleanCode\ErrorCodeEnum;
use CleanCode\Exceptions\ArgsException;

class IntegerArgumentMarshaler implements ArgumentMarshaler
{
    private int $integerValue = 0;

    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        if (strlen($s) <= 2) {
            throw new ArgsException(ErrorCodeEnum::MISSING_INTEGER(), $s);
        }

        $int = substr($s, 2);

        if (!ctype_digit($int)) {
            throw new ArgsException(ErrorCodeEnum::INVALID_INTEGER(), $s);
        }

        $this->integerValue = $int;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->integerValue;
    }
}
