<?php

namespace CleanCode\Chapter14\ArgumentMarshaler;

use CleanCode\Chapter14\ArgumentMarshaler;
use CleanCode\Chapter14\ErrorCodeEnum;
use CleanCode\Chapter14\ArgsException;

class DoubleArgumentMarshaler implements ArgumentMarshaler
{
    private float $doubleValue = 0;

    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        if (strlen($s) <= 2) {
            throw new ArgsException(ErrorCodeEnum::MISSING_DOUBLE(), $s);
        }

        $test = $double = substr($s, 2);

        $dot_index = strpos($test, '.');

        if ($dot_index) {
            $test[$dot_index] = 0;
        }

        if (!ctype_digit($test)) {
            throw new ArgsException(ErrorCodeEnum::INVALID_DOUBLE(), $s);
        }

        $this->doubleValue = $double;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->doubleValue;
    }
}
