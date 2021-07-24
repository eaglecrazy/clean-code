<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;
use CleanCode\Exceptions\NumberFormatException;

class IntegerArgumentMarshaler extends ArgumentMarshaler
{
    private int $integerValue = 0;

    /**
     * @inheritDoc
     * @throws NumberFormatException
     */
    public function set(string $s): void
    {
        if (!ctype_digit($s)) {
            throw new NumberFormatException();
        }

        $this->integerValue = $s;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->integerValue;
    }
}
